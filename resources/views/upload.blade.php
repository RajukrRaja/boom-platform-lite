<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video - Boom Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .upload-container { max-width: 600px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .preview-container { margin-top: 1rem; max-height: 200px; border-radius: 8px; }
        .alert { border-radius: 10px; }
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
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-white">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="upload-container mt-5 pt-5">
        <h2 class="mb-4 text-center">Upload Video</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('videos.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Video Title" value="{{ old('title') }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" placeholder="Describe your video">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="video_type" class="form-label">Video Type</label>
                <select name="video_type" id="video_type" class="form-select" onchange="toggleInputs()" required>
                    <option value="short_form" {{ old('video_type', 'short_form') === 'short_form' ? 'selected' : '' }}>Short Form</option>
                    <option value="long_form" {{ old('video_type') === 'long_form' ? 'selected' : '' }}>Long Form</option>
                </select>
            </div>
            <div id="short_form_inputs" style="display: {{ old('video_type', 'short_form') === 'short_form' ? 'block' : 'none' }};">
                <div class="mb-3">
                    <label for="video_file" class="form-label">Video File (.mp4, max 10MB)</label>
                    <input type="file" name="video_file" id="video_file" class="form-control" accept="video/mp4">
                    <small class="form-text text-muted">Upload a valid MP4 file under 10MB.</small>
                    <video id="video_preview" class="preview-container mt-2" controls style="display: none;"></video>
                </div>
            </div>
            <div id="long_form_inputs" style="display: {{ old('video_type') === 'long_form' ? 'block' : 'none' }};">
                <div class="mb-3">
                    <label for="video_url" class="form-label">Video URL</label>
                    <input type="url" name="video_url" id="video_url" class="form-control" placeholder="e.g., https://youtube.com/watch?v=..." value="{{ old('video_url') }}">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price (₹)</label>
                    <input type="number" name="price" id="price" class="form-control" placeholder="Price in INR (0 for free)" min="0" step="0.01" value="{{ old('price') }}">
                </div>
                <div class="mb-3">
                    <label for="thumbnail" class="form-label">Thumbnail (Optional)</label>
                    <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/jpeg,image/png,image/jpg">
                    <img id="thumbnail_preview" class="preview-container mt-2" style="display: none;" alt="Thumbnail Preview">
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="uploadButton">Upload Video</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleInputs() {
            const type = document.getElementById('video_type').value;
            const shortFormInputs = document.getElementById('short_form_inputs');
            const longFormInputs = document.getElementById('long_form_inputs');
            const videoFile = document.getElementById('video_file');
            const videoUrl = document.getElementById('video_url');
            const price = document.getElementById('price');
            const thumbnail = document.getElementById('thumbnail');

            shortFormInputs.style.display = type === 'short_form' ? 'block' : 'none';
            longFormInputs.style.display = type === 'long_form' ? 'block' : 'none';

            // Set required attributes dynamically
            videoFile.required = type === 'short_form';
            videoUrl.required = type === 'long_form';
            price.required = type === 'long_form';

            // Clear fields when switching
            if (type === 'short_form') {
                videoUrl.value = '';
                price.value = '';
                thumbnail.value = '';
                document.getElementById('thumbnail_preview').style.display = 'none';
            } else {
                videoFile.value = '';
                document.getElementById('video_preview').style.display = 'none';
            }
        }

        // Validate video file
        document.getElementById('video_file').addEventListener('change', function(e) {
            const videoPreview = document.getElementById('video_preview');
            if (e.target.files[0]) {
                const file = e.target.files[0];
                if (!file.type.match('video/mp4')) {
                    alert('Please select a valid MP4 file.');
                    e.target.value = '';
                    videoPreview.style.display = 'none';
                    return;
                }
                if (file.size > 10 * 1024 * 1024) {
                    alert('File size exceeds 10MB limit.');
                    e.target.value = '';
                    videoPreview.style.display = 'none';
                    return;
                }
                videoPreview.src = URL.createObjectURL(file);
                videoPreview.style.display = 'block';
            } else {
                videoPreview.style.display = 'none';
            }
        });

        // Preview thumbnail
        document.getElementById('thumbnail').addEventListener('change', function(e) {
            const thumbnailPreview = document.getElementById('thumbnail_preview');
            if (e.target.files[0]) {
                thumbnailPreview.src = URL.createObjectURL(e.target.files[0]);
                thumbnailPreview.style.display = 'block';
            } else {
                thumbnailPreview.style.display = 'none';
            }
        });

        // Prevent double submission
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const button = document.getElementById('uploadButton');
            button.disabled = true;
            button.innerText = 'Uploading...';
        });

        toggleInputs();
    </script>
</body>
</html>