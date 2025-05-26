<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Log in to Boom Platform to access your account and explore exclusive content.">
    <meta name="robots" content="index, follow">
    <title>Login - Boom Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #312e81;
            --accent: #ec4899;
            --background: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.7);
            --card-blur: blur(10px);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            backdrop-filter: var(--card-blur);
            -webkit-backdrop-filter: var(--card-blur);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: 500;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .nav-link:hover, .nav-link:focus {
            color: var(--accent) !important;
            transform: translateY(-1px);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            padding: 120px 0 80px;
            text-align: center;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
            z-index: 0;
        }

        .hero-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 16px;
            animation: fadeIn 1.2s ease-out;
            position: relative;
            z-index: 1;
        }

        .hero-section p {
            font-size: 1.25rem;
            font-weight: 300;
            max-width: 650px;
            margin: 0 auto;
            opacity: 0.85;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: var(--card-blur);
            -webkit-backdrop-filter: var(--card-blur);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-top: -100px;
            max-width: 600px;
            animation: slideUp 1s ease-out;
        }

        .form-control {
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.9);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
            outline: none;
            background: white;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 24px;
            animation: fadeIn 0.6s ease-in;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: var(--card-blur);
        }

        .form-label {
            font-weight: 500;
            color: #111827;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .link-register {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease, text-decoration 0.3s ease;
        }

        .link-register:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(40px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            .hero-section { padding: 80px 0 50px; }
            .hero-section h1 { font-size: 2.5rem; }
            .login-card { margin: -60px 20px 0; padding: 30px; }
        }

        @media (max-width: 480px) {
            .hero-section h1 { font-size: 2rem; }
            .hero-section p { font-size: 1.1rem; }
            .login-card { padding: 20px; }
            .form-control { font-size: 0.95rem; }
            .btn-primary { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top" aria-label="Main navigation">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Boom Platform</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('login') }}" aria-current="page">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Signup</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section" role="banner">
        <div class="container">
            <h1>Access Your Boom Account</h1>
            <p>Log in to explore exclusive content and support creators.</p>
        </div>
    </div>

    <div class="container">
        <div class="login-card mx-auto" role="main">
            @if(session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf
                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <input name="username" type="text" class="form-control @error('username') is-invalid @enderror" id="username" value="{{ old('username') }}" required aria-describedby="usernameError">
                    @error('username')
                        <div id="usernameError" class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" required aria-describedby="passwordError">
                    @error('password')
                        <div id="passwordError" class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100">Log In</button>
                <p class="text-center mt-4">Don’t have an account? <a href="{{ route('register') }}" class="link-register">Sign Up</a></p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const form = event.target;
            let valid = true;

            const username = form.querySelector('#username');
            const password = form.querySelector('#password');

            username.classList.remove('is-invalid');
            password.classList.remove('is-invalid');

            if (!username.value.trim()) {
                username.classList.add('is-invalid');
                valid = false;
            }

            if (!password.value.trim()) {
                password.classList.add('is-invalid');
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
