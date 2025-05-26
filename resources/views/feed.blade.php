<!-- resources/views/feed.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Boom Feed - Boom Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100vh;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background: #000;
            color: #fff;
            -webkit-user-select: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .navbar {
            background: linear-gradient(45deg, #4f46e5, #312e81);
            padding: 0.5rem 1rem;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .feed-container {
            height: 100vh;
            overflow-y: scroll;
            scroll-snap-type: y mandatory;
            -webkit-overflow-scrolling: touch;
            max-width: 600px;
            margin: 0 auto;
            touch-action: pan-y;
            overscroll-behavior-y: none;
            scroll-behavior: auto;
            will-change: transform;
        }

        .video-card {
            position: relative;
            width: 100%;
            height: 100vh;
            scroll-snap-align: start;
            scroll-snap-stop: always;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .video-card video {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .video-card img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .video-card.loading::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5) url('https://i.gifer.com/origin/34/3438d3b1f8a32d73d3c4f9b562e75e0b_w200.gif') no-repeat center;
            background-size: 50px;
            z-index: 2;
        }

        .video-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0));
            padding: 1rem;
            z-index: 2;
            color: white;
            pointer-events: none;
        }

        .video-overlay * {
            pointer-events: auto;
        }

        .video-overlay h5 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .video-overlay p {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .btn-buy {
            background: #ec4899;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .btn-buy:hover {
            background: #be185d;
        }

        .btn-watch {
            background: #4caf50;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .btn-watch:hover {
            background: #388e3c;
        }

        .btn-like {
            background: transparent;
            color: white;
            border: 1px solid white;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn-like.liked {
            background: #ff0000;
            border-color: #ff0000;
        }

        .btn-like:hover {
            background: #ff0000;
            border-color: #ff0000;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ route('feed') }}">Boom Platform</a>
            <div class="ms-auto">
                <span class="text-white me-3">Wallet: ₹{{ Auth::user()->wallet_balance }}</span>
                <a href="{{ route('logout') }}" class="text-white">Logout</a>
            </div>
        </div>
    </nav>

    <div class="feed-container">
        @forelse ($videos as $video)
            <div class="video-card" data-video-id="{{ $video->id }}">
                @if ($video->video_type === 'short_form')
                    <video class="video-player" muted loop playsinline preload="metadata" loading="lazy">
                        <source src="{{ $video->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" loading="lazy">
                @endif
                <div class="video-overlay">
                    <h5>{{ $video->title }}</h5>
                    <p class="text-muted">By {{ $video->creator_name }}</p>
                    <p class="text-muted">Likes: {{ $video->likes_count }}</p>
                    @if ($video->video_type === 'long_form')
                        <p class="text-muted">Price: ₹{{ $video->price }}</p>
                        @if ($video->price > 0 && !$video->is_purchased)
                            <form action="{{ route('videos.purchase', $video->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-buy">Buy for ₹{{ $video->price }}</button>
                            </form>
                        @else
                            <a href="{{ route('videos.show', $video->id) }}" class="btn btn-watch">Watch</a>
                        @endif
                    @else
                        <a href="{{ route('videos.show', $video->id) }}" class="btn btn-watch">Watch</a>
                    @endif
                    <form action="{{ route('videos.like', $video->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-like {{ $video->is_liked ? 'liked' : '' }}">
                            {{ $video->is_liked ? 'Unlike' : 'Like' }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center mt-5">
                <p>No videos available. Upload one to get started!</p>
                <a href="{{ route('upload.form') }}" class="btn btn-primary">Upload Video</a>
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const video = entry.target.querySelector('.video-player');
                    if (!video) return;

                    if (entry.isIntersecting) {
                        video.play().catch(err => console.log('Autoplay failed:', err));
                    } else {
                        video.pause();
                        video.currentTime = 0;
                    }
                });
            }, {
                threshold: 0.5
            });

            document.querySelectorAll('.video-card').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</body>
</html>