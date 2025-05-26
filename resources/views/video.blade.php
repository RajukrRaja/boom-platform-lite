<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $video->title }} - Boom Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }
        .video-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .video-player {
            width: 100%;
            height: 400px;
            border-radius: 10px;
        }
        .comments-section {
            max-height: 300px;
            overflow-y: auto;
        }
        .comment {
            background: #fff;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top" style="background: linear-gradient(90deg, #4f46e5, #312e81);">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ route('home') }}">Boom Platform</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('feed') }}">Feed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('upload.form') }}">Upload</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-white">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="video-container mt-5 pt-5">
        <h2>{{ $video->title }}</h2>
        <p class="text-muted">By {{ $video->user->username }}</p>
        @if ($video->is_purchased || $video->price == 0 || $video->video_type === 'short_form')
            @if ($video->video_type === 'short_form')
                <video class="video-player" controls autoplay muted>
                    <source src="{{ $video->video_url }}" type="video/mp4">
                </video>
            @else
                <iframe class="video-player" src="{{ $video->video_url }}" frameborder="0" allowfullscreen></iframe>
            @endif
        @else
            <div class="alert alert-warning">Purchase this video for ₹{{ $video->price }} to watch.</div>
            <form action="{{ route('videos.purchase', $video->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-buy">Buy for ₹{{ $video->price }}</button>
            </form>
        @endif

        <div class="mt-4">
            <h4>Gift the Creator</h4>
            <form action="{{ route('videos.gift', $video->id) }}" method="POST">
                @csrf
                <select name="amount" class="form-select mb-2" style="width: 150px;">
                    <option value="10">₹10</option>
                    <option value="50">₹50</option>
                    <option value="100">₹100</option>
                </select>
                <button type="submit" class="btn btn-primary">Send Gift</button>
            </form>
        </div>

        <div class="comments-section mt-4">
            <h4>Comments</h4>
            @foreach ($video->comments as $comment)
                <div class="comment">
                    <strong>{{ $comment->user->username }}</strong>: {{ $comment->comment }}
                </div>
            @endforeach
            @auth
                <form action="{{ route('videos.comment', $video->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="input-group">
                        <textarea name="comment" class="form-control" placeholder="Add a comment" required></textarea>
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            @endauth
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        @if (session('success') && str_contains(session('success'), 'Gift'))
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        @endif
    </script>
</body>
</html>