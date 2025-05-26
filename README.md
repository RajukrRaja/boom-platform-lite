StarVibe - Unleash the Entertainment
StarVibe is a vibrant, Laravel-based video streaming platform designed to connect creators and audiences through engaging content. With a sleek, modern interface, StarVibe allows users to stream epic videos, support creators, and join a global community of entertainment enthusiasts. Whether you're watching short clips, purchasing premium content, or uploading your own masterpieces, StarVibe offers a dynamic experience powered by a neon-fueled, futuristic aesthetic.
Table of Contents

Features
Technologies
Installation
Usage
Routes
File Structure
Contributing
License
Contact

Features

Dynamic Video Streaming: Watch free or premium videos with a seamless, responsive interface.
Creator Support: Purchase premium content, send gifts, like, and comment to engage with creators.
Interactive Carousel: Browse featured videos with a smooth-scrolling, 3D-effect carousel.
User Authentication: Secure login, registration, and dashboard for managing user activities.
Responsive Design: Built with Tailwind CSS for a mobile-friendly, visually appealing experience.
Video Background: Immersive hero section with a looping video background for a cinematic vibe.
Community Engagement: Connect with fans worldwide through comments and sharing.

Technologies

Backend: Laravel 10.x (PHP 8.1+)
Frontend: Blade templates, Tailwind CSS 2.2.19, custom CSS for 3D effects and gradients
JavaScript: Minimal vanilla JS for carousel scrolling
Storage: Laravel Storage for handling video and thumbnail uploads
Database: Configurable (MySQL/PostgreSQL recommended)
Dependencies: Composer for PHP packages, npm for frontend assets

Installation
Follow these steps to set up StarVibe locally:
Prerequisites

PHP 8.1 or higher
Composer
Node.js and npm
MySQL/PostgreSQL (or any Laravel-supported database)
FFmpeg (for video processing, if applicable)
Git

Steps

Clone the Repository:
git clone https://github.com/your-username/starvibe.git
cd starvibe


Install Dependencies:
composer install
npm install


Configure Environment:

Copy .env.example to .env:cp .env.example .env


Update .env with your database credentials and storage settings:DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=starvibe
DB_USERNAME=root
DB_PASSWORD=


Set the application key:php artisan key:generate




Set Up Storage:

Link storage for public assets (e.g., video thumbnails, hero video):php artisan storage:link


Ensure storage/app/public/videos/hero-bg.mp4 exists or update the path in welcome.blade.php.


Run Migrations:
php artisan migrate


Compile Assets:
npm run dev


Start the Development Server:
php artisan serve

Access the app at http://localhost:8000.


Usage

Home Page: Browse featured videos, watch free content, or purchase premium videos.
Authentication: Register or log in to access the dashboard, upload videos, or interact with content.
Video Interaction:
Free Videos: Click "Watch Free" to view.
Premium Videos: Log in and click "Buy Now" to purchase (requires authentication).
Upload: Authenticated users can upload videos via the /upload route.


Dashboard: Manage your videos, view engagement metrics, and interact with the community.
Carousel: Scroll through featured videos using mouse wheel or touch gestures.

Routes
StarVibe uses Laravel's routing system. Key routes include:



Method
URI
Name
Description



GET
/
home
Displays the home page with featured videos


GET
/register
register
Shows registration form


POST
/register
-
Handles user registration


GET
/login
login
Shows login form


POST
/login
-
Handles user login


POST
/logout
logout
Logs out the user


GET
/dashboard
dashboard
User dashboard (authenticated)


GET
/feed
feed
User feed (authenticated)


GET
/upload
upload.form
Shows video upload form (authenticated)


POST
/upload
videos.upload
Handles video upload (authenticated)


POST
/videos/{videoId}/purchase
videos.purchase
Handles video purchase (authenticated)


POST
/videos/{videoId}/comment
videos.comment
Adds a comment (authenticated)


POST
/videos/{videoId}/gift
videos.gift
Sends a gift (authenticated)


POST
/videos/{videoId}/like
videos.like
Likes a video (authenticated)


GET
/videos/{videoId}
videos.show
Displays a video


Note: Routes under middleware('auth') require user authentication.
File Structure
starvibe/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── VideoController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Video.php
├── public/
│   ├── storage/           # Symlinked storage for videos and thumbnails
│   ├── images/            # Placeholder images (e.g., placeholder.jpg)
├── resources/
│   ├── views/
│   │   ├── welcome.blade.php  # Main homepage view
│   │   ├── dashboard.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   ├── css/               # Tailwind CSS (compiled)
│   ├── js/                # JavaScript (e.g., carousel script)
├── routes/
│   ├── web.php            # Route definitions
├── .env                   # Environment configuration
├── composer.json          # PHP dependencies
├── package.json           # Frontend dependencies

Contributing
We welcome contributions to StarVibe! To contribute:

Fork the repository.
Create a feature branch (git checkout -b feature/your-feature).
Commit your changes (git commit -m 'Add your feature').
Push to the branch (git push origin feature/your-feature).
Open a pull request.

Please ensure your code follows Laravel coding standards and includes tests where applicable.
License
StarVibe is licensed under the MIT License. See LICENSE for details.
Contact

Website: www.starvibe.com
Email: info@starvibe.com
GitHub Issues: Report bugs or suggest features

© 2025 StarVibe. Let’s create the future of entertainment!
