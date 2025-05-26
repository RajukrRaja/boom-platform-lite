# 🌟 StarVibe – Unleash the Entertainment

**StarVibe** is a vibrant, Laravel-based video streaming platform that connects creators and audiences through engaging content. With a sleek, futuristic interface, users can stream videos, support creators, and be part of a global entertainment community.

Whether you’re watching short clips, buying premium content, or uploading your own masterpieces, **StarVibe** offers a dynamic, immersive experience.

---

## 📚 Table of Contents

- [Features](#features)
- [Technologies](#technologies)
- [Installation](#installation)
- [Usage](#usage)
- [Routes](#routes)
- [File Structure](#file-structure)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

---

## ✨ Features

- 🎥 **Dynamic Video Streaming** – Seamless experience for both free and premium content.
- 💸 **Creator Support** – Purchase premium content, send gifts, like, and comment.
- 🎠 **Interactive Carousel** – Smooth-scrolling, 3D-effect video showcase.
- 🔐 **User Authentication** – Secure registration, login, and dashboard.
- 📱 **Responsive Design** – Tailwind CSS ensures mobile-friendly UI.
- 🎬 **Video Background** – Immersive hero section with cinematic looping video.
- 🌍 **Community Engagement** – Global interactions through comments and sharing.

---

## ⚙️ Technologies

- **Backend:** Laravel 10.x (PHP 8.1+)
- **Frontend:** Blade templates, Tailwind CSS 2.2.19, custom CSS (3D effects & gradients)
- **JavaScript:** Vanilla JS (carousel)
- **Storage:** Laravel Storage (videos, thumbnails)
- **Database:** MySQL/PostgreSQL (configurable)
- **Package Managers:** Composer (PHP), npm (frontend)

---

## 📦 Installation

### Prerequisites

- PHP 8.1+
- Composer
- Node.js & npm
- MySQL/PostgreSQL
- FFmpeg (optional - for video processing)
- Git

### Steps

```bash
# Clone the repository
git clone https://github.com/your-username/starvibe.git
cd starvibe

# Install dependencies
composer install
npm install

# Copy and configure .env
cp .env.example .env

# Update DB credentials in `.env`
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=starvibe
DB_USERNAME=root
DB_PASSWORD=

# Generate application key
php artisan key:generate

# Link storage
php artisan storage:link

# Run database migrations
php artisan migrate

# Compile frontend assets
npm run dev

# Start development server
php artisan serve
```

Visit: [http://localhost:8000](http://localhost:8000)

---

## 🚀 Usage

- **Home Page:** Watch featured videos or buy premium ones.
- **Authentication:** Register/Login to access dashboard & upload.
- **Free Videos:** Click “Watch Free”.
- **Premium Videos:** Login → “Buy Now”.
- **Upload Videos:** Visit `/upload` after login.
- **Dashboard:** View uploads, likes, comments, etc.
- **Carousel:** Navigate using mouse scroll or swipe.

---

## 🔁 Routes

| Method | URI                               | Name              | Description                        |
|--------|-----------------------------------|-------------------|------------------------------------|
| GET    | /                                 | home              | Home page                          |
| GET    | /register                         | register          | Registration form                  |
| POST   | /register                         | -                 | Handle registration                |
| GET    | /login                            | login             | Login form                         |
| POST   | /login                            | -                 | Handle login                       |
| POST   | /logout                           | logout            | Logout                             |
| GET    | /dashboard                        | dashboard         | User dashboard                     |
| GET    | /feed                             | feed              | User feed                          |
| GET    | /upload                           | upload.form       | Upload form                        |
| POST   | /upload                           | videos.upload     | Handle video upload                |
| POST   | /videos/{videoId}/purchase        | videos.purchase   | Purchase premium video             |
| POST   | /videos/{videoId}/comment         | videos.comment    | Add comment                        |
| POST   | /videos/{videoId}/gift            | videos.gift       | Send gift                          |
| POST   | /videos/{videoId}/like            | videos.like       | Like video                         |
| GET    | /videos/{videoId}                 | videos.show       | Show video                         |

> ⚠️ Routes protected by `auth` middleware require authentication.

---

## 🗂️ File Structure

```
starvibe/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── VideoController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Video.php
│
├── public/
│   ├── storage/             # Symlinked storage for assets
│   ├── images/              # Placeholder assets
│
├── resources/
│   ├── views/
│   │   ├── welcome.blade.php
│   │   ├── dashboard.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   ├── css/                 # Tailwind CSS (compiled)
│   ├── js/                  # Carousel & interactivity scripts
│
├── routes/
│   ├── web.php              # Route definitions
│
├── .env                     # Environment variables
├── composer.json            # PHP dependencies
├── package.json             # JS dependencies
```

---

## 🤝 Contributing

1. Fork the repo.
2. Create your feature branch: `git checkout -b feature/your-feature`.
3. Commit your changes: `git commit -m "Add feature"`.
4. Push to the branch: `git push origin feature/your-feature`.
5. Open a pull request.

✅ Follow Laravel coding standards.  
✅ Include tests where possible.

---

## 📄 License

Licensed under the [MIT License](LICENSE).

---

## 📬 Contact

- 🌐 Website: [www.starvibe.com](https://www.starvibe.com)
- 📧 Email: info@starvibe.com
- 🐞 GitHub Issues: [Submit bugs & ideas](https://github.com/your-username/starvibe/issues)

---

© 2025 **StarVibe**. Let’s create the future of entertainment!
