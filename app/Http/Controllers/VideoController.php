<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Purchase;
use App\Models\Comment;
use App\Models\Gift;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class VideoController extends Controller
{
    /**
     * Display the welcome page with featured videos.
     */
    public function index(): View
    {
        $cacheKey = 'welcome_videos';
        $featuredVideos = Cache::remember($cacheKey, 600, function () {
            return Video::with('user')
                ->orderByDesc('created_at')
                ->take(6)
                ->get();
        });

        $featuredVideos->transform(function (Video $video) {
            $userId = Auth::id();
            $video->is_purchased = $userId ? $video->purchases()->where('user_id', $userId)->exists() : false;
            $video->is_liked = $userId ? $video->likes()->where('user_id', $userId)->exists() : false;
            $video->likes_count = $video->likes()->count();
            $video->video_url = $video->video_path ? Storage::url($video->video_path) : $video->video_url;
            $video->thumbnail_url = $video->thumbnail ? Storage::url($video->thumbnail) : asset('images/default_thumbnail.jpg');
            $video->creator_name = $video->user->username ?? 'Unknown';
            return $video;
        });

        $heroVideo = [
            'type' => 'local',
            'path' => 'videos/hero-bg.mp4',
        ];

        return view('welcome', compact('featuredVideos', 'heroVideo'));
    }

    /**
     * Display the video upload form.
     */
    public function showUploadForm(): View
    {
        return view('upload');
    }

    /**
     * Handle video upload with conditional validation for short-form and long-form videos.
     */
    public function upload(Request $request): RedirectResponse
    {
        try {
            $rules = [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'video_type' => 'required|in:short_form,long_form',
            ];

            if ($request->input('video_type') === 'short_form') {
                $rules['video_file'] = 'required|file|mimes:mp4|max:10240';
            } else {
                $rules['video_url'] = 'required|url';
                $rules['price'] = 'required|numeric|min:0';
                $rules['thumbnail'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048'; // Fixed syntax
            }

            $request->validate($rules);

            DB::beginTransaction();

            $video = new Video();
            $video->user_id = Auth::id();
            $video->title = $request->title;
            $video->description = $request->description;
            $video->video_type = $request->video_type;

            if ($video->video_type === 'short_form') {
                if (!$request->hasFile('video_file')) {
                    throw new \Exception('Video file is required for short form videos.');
                }
                $file = $request->file('video_file');

                if (!$file->isValid() || $file->getMimeType() !== 'video/mp4') {
                    throw new \Exception('Invalid video file. Only MP4 files are accepted.');
                }

                $path = $file->store('videos', 'public');
                if (!$path) {
                    throw new \Exception('Failed to store video file. Check storage permissions.');
                }
                $video->video_path = $path;
            } else {
                $video->video_url = $request->video_url;
                $video->price = $request->price;

                if ($request->hasFile('thumbnail')) {
                    $thumbnail = $request->file('thumbnail');
                    if (!$thumbnail->isValid()) {
                        throw new \Exception('Invalid thumbnail file. Please upload a valid image.');
                    }
                    $thumbnailPath = $thumbnail->store('thumbnails', 'public');
                    $video->thumbnail = $thumbnailPath;
                } else {
                    $video->thumbnail = 'thumbnails/default.jpg';
                }
            }

            $video->save();

            DB::commit();

            return redirect()->route('feed')->with('success', 'Video uploaded successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Video upload failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'video_type' => $request->video_type,
                'file_size' => $request->hasFile('video_file') ? $request->file('video_file')->getSize() : null,
                'file_mime' => $request->hasFile('video_file') ? $request->file('video_file')->getMimeType() : null,
            ]);
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the unified video feed optimized for Instagram-like vertical scrolling.
     */
    public function feed(Request $request): View|JsonResponse
    {
        $page = $request->input('page', 1);
        $cacheKey = 'videos_feed_' . $page;

        $videos = Cache::remember($cacheKey, 600, function () {
            return Video::with('user')
                ->orderByDesc('created_at')
                ->paginate(5);
        });

        $videos->getCollection()->transform(function (Video $video) {
            $userId = Auth::id();
            $video->is_purchased = $userId ? $video->purchases()->where('user_id', $userId)->exists() : false;
            $video->is_liked = $userId ? $video->likes()->where('user_id', $userId)->exists() : false;
            $video->likes_count = $video->likes()->count();
            $video->video_url = $video->video_path ? Storage::url($video->video_path) : $video->video_url;
            $video->thumbnail_url = $video->thumbnail ? Storage::url($video->thumbnail) : asset('images/default_thumbnail.jpg');
            $video->creator_name = $video->user->username ?? 'Unknown';
            return $video;
        });

        if ($request->ajax()) {
            $view = view('partials.video-card', compact('videos'))->render();
            return response()->json([
                'html' => $view,
                'next_page_url' => $videos->nextPageUrl(),
            ]);
        }

        return view('feed', compact('videos'));
    }

    /**
     * Handle video purchase with wallet balance check and transaction.
     */
    public function purchase(Request $request, int $videoId): RedirectResponse
    {
        $userId = Auth::id();
        $key = 'purchase:' . $userId . ':' . $videoId;

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return redirect()->back()->with('error', 'Too many purchase attempts. Please try again later.');
        }

        $video = Video::findOrFail($videoId);

        if ($video->video_type !== 'long_form' || $video->price <= 0) {
            return redirect()->back()->with('error', 'This video is free or cannot be purchased.');
        }

        $user = Auth::user();

        if ($user->wallet_balance < $video->price) {
            return redirect()->back()->with('error', 'Insufficient wallet balance.');
        }

        try {
            DB::beginTransaction();

            $user->wallet_balance -= $video->price;
            $user->save();

            Purchase::create([
                'user_id' => $user->id,
                'video_id' => $video->id,
                'amount' => $video->price,
            ]);

            DB::commit();

            RateLimiter::hit($key, 60);

            return redirect()->back()->with('success', 'Video purchased successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Purchase failed: ' . $e->getMessage(), ['user_id' => $userId, 'video_id' => $videoId]);
            return redirect()->back()->with('error', 'Purchase failed. Please try again.');
        }
    }

    /**
     * Add a comment to a video with sanitization.
     */
    public function comment(Request $request, int $videoId): RedirectResponse
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $comment = new Comment();
            $comment->user_id = Auth::id();
            $comment->video_id = $videoId;
            $comment->comment = Str::limit(strip_tags($request->input('comment')), 500);
            $comment->save();

            DB::commit();

            return redirect()->back()->with('success', 'Comment added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Comment creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'video_id' => $videoId,
                'comment' => $request->input('comment'),
            ]);
            return redirect()->back()->with('error', 'Failed to add comment. Please try again.');
        }
    }

    /**
     * Handle gifting to a creator with transaction and rate-limiting.
     */
    public function gift(Request $request, int $videoId): RedirectResponse
    {
        $userId = Auth::id();
        $key = 'gift:' . $userId . ':' . $videoId;

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return redirect()->back()->with('error', 'Too many gift attempts. Please try again later.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $video = Video::findOrFail($videoId);
        $user = Auth::user();

        if ($user->wallet_balance < $request->input('amount')) {
            return redirect()->back()->with('error', 'Insufficient wallet balance.');
        }

        try {
            DB::beginTransaction();

            $user->wallet_balance -= $request->input('amount');
            $user->save();

            Gift::create([
                'user_id' => $user->id,
                'creator_id' => $video->user_id,
                'video_id' => $video->id,
                'amount' => $request->input('amount'),
            ]);

            DB::commit();

            RateLimiter::hit($key, 60);

            return redirect()->back()->with('success', 'Gift sent successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gift failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send gift. Please try again.');
        }
    }

    /**
     * Toggle like/unlike for a video.
     */
    public function like(Request $request, int $videoId): RedirectResponse
    {
        $userId = Auth::id();
        $key = 'like:' . $userId . ':' . $videoId;

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return redirect()->back()->with('error', 'Too many like attempts. Please try again later.');
        }

        $video = Video::findOrFail($videoId);

        try {
            DB::beginTransaction();

            $existingLike = Like::where('user_id', $userId)->where('video_id', $videoId)->first();

            if ($existingLike) {
                $existingLike->delete();
                $message = 'Video unliked successfully.';
            } else {
                Like::create([
                    'user_id' => $userId,
                    'video_id' => $videoId,
                ]);
                $message = 'Video liked successfully.';
            }

            DB::commit();

            RateLimiter::hit($key, 60);

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Like action failed: ' . $e->getMessage(), ['user_id' => $userId, 'video_id' => $videoId]);
            return redirect()->back()->with('error', 'Failed to process like. Please try again.');
        }
    }

    /**
     * Display a specific video.
     */
    public function show(int $videoId): View
    {
        $video = Video::with('user', 'comments')->findOrFail($videoId);
        $video->video_url = $video->video_path ? Storage::url($video->video_path) : $video->video_url;
        $video->thumbnail_url = $video->thumbnail ? Storage::url($video->thumbnail) : asset('images/default_thumbnail.jpg');
        $video->creator_name = $video->user->username ?? 'Unknown';
        $video->is_purchased = Auth::id() ? $video->purchases()->where('user_id', Auth::id())->exists() : false;
        $video->is_liked = Auth::id() ? $video->likes()->where('user_id', Auth::id())->exists() : false;
        $video->likes_count = $video->likes()->count();

        return view('video', compact('video'));
    }

    /**
     * Parse video URL to handle multiple platforms and formats.
     */
    private function parseVideoUrl(string $url): ?array
    {
        $patterns = [
            'youtube' => '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([^\&\?\/]+)/',
            'vimeo' => '/(?:https?:\/\/)?(?:www\.)?vimeo\.com\/(\d+)/',
            'tiktok' => '/(?:https?:\/\/)?(?:www\.)?tiktok\.com\/@[\w.-]+\/video\/(\d+)/',
        ];

        foreach ($patterns as $platform => $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return ['platform' => $platform, 'id' => $matches[1]];
            }
        }

        return null;
    }
}