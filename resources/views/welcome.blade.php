<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StarVibe - Unleash the Entertainment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #0a0a0a;
            color: #ffffff;
        }
        .hero-bg {
            position: relative;
            height: 80vh;
            overflow: hidden;
        }
        .hero-video {
            object-fit: cover;
            width: 100%;
            height: 100%;
            opacity: 0.7;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8));
        }
        .card-3d {
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            transform-style: preserve-3d;
        }
        .card-3d:hover {
            transform: translateY(-10px) rotateX(10deg) rotateY(10deg);
            box-shadow: 0 15px 30px rgba(0, 255, 255, 0.3);
        }
        .carousel {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
        .carousel-item {
            flex: 0 0 auto;
            scroll-snap-align: start;
        }
        .btn-glow {
            background: linear-gradient(90deg, #ff00ff, #00ffff);
            transition: all 0.3s ease;
        }
        .btn-glow:hover {
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="bg-black bg-opacity-80 shadow-lg sticky top-0 z-50">
        <nav class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="text-3xl font-extrabold text-cyan-400">StarVibe</div>
            <div class="flex space-x-6">
                <a href="#featured" class="text-gray-300 hover:text-cyan-400">Discover</a>
                <a href="#join" class="text-gray-300 hover:text-cyan-400">Join</a>
                <a href="mailto:info@starvibe.com" class="text-gray-300 hover:text-cyan-400">Contact</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-cyan-400">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-cyan-400">Login</a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Hero Section with Video Background -->
    <section class="hero-bg flex items-center justify-center">
        <video class="hero-video" autoplay muted loop>
            <source src="{{ asset('storage/videos/hero-bg.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="hero-overlay flex items-center">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 animate-pulse">Unleash Your StarVibe</h1>
                <p class="text-lg md:text-2xl mb-6 max-w-3xl mx-auto">
                    Stream epic videos, connect with creators, and shine in our vibrant entertainment universe!
                </p>
                <a href="{{ route('register') }}" class="btn-glow text-black px-8 py-3 rounded-full font-semibold text-lg">
                    Join the Vibe
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Content Carousel -->
    <section id="featured" class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8 text-cyan-400">Featured Vibes</h2>
            <div class="carousel">
                @forelse ($featuredVideos as $video)
                    <div class="carousel-item card-3d bg-gray-800 rounded-lg mx-4 w-72 p-4">
                        <img 
                            src="{{ $video->thumbnail ? Storage::url($video->thumbnail) : asset('images/placeholder.jpg') }}" 
                            alt="{{ $video->title ?? 'Video Thumbnail' }}" 
                            class="w-full h-48 object-cover rounded-lg mb-4"
                            loading="lazy"
                        >
                        <h3 class="text-lg font-semibold text-white truncate">{{ $video->title ?? 'Untitled Video' }}</h3>
                        <p class="text-sm text-gray-400">By {{ $video->user->name ?? 'Unknown' }}</p>
                        @if ($video->price > 0)
                            <p class="text-sm text-cyan-400">₹{{ number_format($video->price, 2) }}</p>
                            <a 
                                href="{{ route('videos.purchase', $video->id) }}" 
                                class="btn-glow text-black px-4 py-2 rounded-lg mt-2 inline-block"
                                @guest onclick="event.preventDefault(); alert('Please log in to purchase this video.'); window.location.href='{{ route('login') }}';" @endguest
                            >
                                Buy Now
                            </a>
                        @else
                            <a 
                                href="{{ route('videos.show', $video->id) }}" 
                                class="btn-glow text-black px-4 py-2 rounded-lg mt-2 inline-block"
                            >
                                Watch Free
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-gray-400">No featured videos available at the moment.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Why StarVibe Section -->
    <section class="py-12 bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8 text-cyan-400">Why Choose StarVibe?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card-3d bg-gray-800 p-6 rounded-lg text-center">
                    <h3 class="text-xl font-semibold text-cyan-400 mb-2">Epic Content</h3>
                    <p class="text-gray-300">Discover short clips and full-length masterpieces curated for you.</p>
                </div>
                <div class="card-3d bg-gray-800 p-6 rounded-lg text-center">
                    <h3 class="text-xl font-semibold text-cyan-400 mb-2">Creator Power</h3>
                    <p class="text-gray-300">Support creators with gifts and unlock exclusive vibes.</p>
                </div>
                <div class="card-3d bg-gray-800 p-6 rounded-lg text-center">
                    <h3 class="text-xl font-semibold text-cyan-400 mb-2">Community Vibes</h3>
                    <p class="text-gray-300">Comment, share, and connect with fans across the globe.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section id="join" class="py-12 bg-black">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4 text-cyan-400">Ready to Shine?</h2>
            <p class="text-lg text-gray-300 mb-6 max-w-xl mx-auto">
                Upload your videos, engage with your audience, and become a star on StarVibe!
            </p>
            <a href="{{ route('register') }}" class="btn-glow text-black px-8 py-3 rounded-full font-semibold text-lg">
                Start Your Journey
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-6">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm text-gray-300">
                StarVibe | <a href="https://www.starvibe.com" class="text-cyan-400 hover:underline">www.starvibe.com</a> | 
                <a href="mailto:info@starvibe.com" class="text-cyan-400 hover:underline">info@starvibe.com</a>
            </p>
            <p class="text-sm text-gray-400 mt-2">© 2025 StarVibe. Let’s create the future of entertainment.</p>
        </div>
    </footer>

    <script>
        // Smooth scroll for carousel
        const carousel = document.querySelector('.carousel');
        if (carousel) {
            carousel.addEventListener('wheel', (e) => {
                e.preventDefault();
                carousel.scrollLeft += e.deltaY;
            });
        }
    </script>
</body>
</html>