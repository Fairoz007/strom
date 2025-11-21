<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Storm Breaker - Image Gallery</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        body.dark-mode {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        body.dark-mode .header {
            background: rgba(45, 45, 68, 0.95);
            color: #fff;
        }

        .header h1 {
            color: #667eea;
            font-size: 1.8rem;
            margin: 0;
        }

        body.dark-mode .header h1 { color: #fff; }

        .controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        body.dark-mode .controls {
            background: rgba(45, 45, 68, 0.95);
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn:hover { transform: translateY(-2px); }

        .btn-primary { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; }
        .btn-success { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white; }
        .btn-danger { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; }
        .btn-secondary { background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; }

        .filter-input {
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            flex: 1;
            min-width: 200px;
        }

        body.dark-mode .filter-input {
            background: #1a1a2e;
            border-color: #3d3d5c;
            color: #fff;
        }

        .stats-bar {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-around;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        body.dark-mode .stats-bar {
            background: rgba(45, 45, 68, 0.95);
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }

        body.dark-mode .stat-value { color: #fff; }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        body.dark-mode .stat-label { color: #aaa; }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 10px;
        }

        .gallery-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        body.dark-mode .gallery-item {
            background: #2d2d44;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .gallery-info {
            padding: 15px;
        }

        .gallery-info h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 1rem;
        }

        body.dark-mode .gallery-info h4 { color: #fff; }

        .gallery-info p {
            margin: 5px 0;
            font-size: 0.85rem;
            color: #666;
        }

        body.dark-mode .gallery-info p { color: #aaa; }

        .gallery-actions {
            display: flex;
            gap: 5px;
            margin-top: 10px;
        }

        .gallery-actions .btn {
            padding: 5px 10px;
            font-size: 0.85rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            overflow: auto;
        }

        .modal-content {
            margin: 2% auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 40px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-info {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 600px;
        }

        body.dark-mode .modal-info {
            background: rgba(45, 45, 68, 0.95);
            color: #fff;
        }

        .no-images {
            text-align: center;
            padding: 50px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        body.dark-mode .no-images {
            background: rgba(45, 45, 68, 0.95);
            color: #fff;
        }

        .no-images i {
            font-size: 5rem;
            color: #ccc;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .gallery {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            
            .controls {
                flex-direction: column;
            }

            .stats-bar {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-images"></i> Image Gallery</h1>
        <div style="display: flex; gap: 10px;">
            <button class="btn btn-secondary" onclick="toggleTheme()">
                <i class="fas fa-moon" id="theme-icon"></i>
            </button>
            <button class="btn btn-primary" onclick="location.href='panel-v5.php'">
                <i class="fas fa-arrow-left"></i> Back to Panel
            </button>
        </div>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-value" id="total-images">0</div>
            <div class="stat-label">Total Images</div>
        </div>
        <div class="stat-item">
            <div class="stat-value" id="filtered-images">0</div>
            <div class="stat-label">Displayed</div>
        </div>
        <div class="stat-item">
            <div class="stat-value" id="total-size">0 MB</div>
            <div class="stat-label">Total Size</div>
        </div>
    </div>

    <div class="controls">
        <input type="text" id="search-input" class="filter-input" placeholder="🔍 Search by filename, date, or source...">
        <select id="sort-select" class="filter-input" style="flex: 0 0 auto; min-width: 150px;">
            <option value="newest">Newest First</option>
            <option value="oldest">Oldest First</option>
            <option value="name">Name A-Z</option>
            <option value="size">Size Large-Small</option>
        </select>
        <button class="btn btn-success" onclick="loadGallery()">
            <i class="fas fa-sync"></i> Refresh
        </button>
        <button class="btn btn-danger" onclick="clearAll()">
            <i class="fas fa-trash"></i> Clear All
        </button>
    </div>

    <div id="gallery-container" class="gallery"></div>

    <!-- Modal -->
    <div id="imageModal" class="modal" onclick="closeModal(event)">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modal-image">
        <div class="modal-info" id="modal-info"></div>
    </div>

    <script>
        let allImages = [];

        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            const icon = document.getElementById('theme-icon');
            icon.className = document.body.classList.contains('dark-mode') ? 'fas fa-sun' : 'fas fa-moon';
            localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
        }

        // Load theme
        if (localStorage.getItem('theme') === 'dark') {
            toggleTheme();
        }

        function loadGallery() {
            fetch('list_images.php')
                .then(response => response.json())
                .then(data => {
                    allImages = data.images || [];
                    updateStats();
                    displayGallery();
                })
                .catch(error => {
                    console.error('Error loading images:', error);
                    showNoImages();
                });
        }

        function updateStats() {
            document.getElementById('total-images').textContent = allImages.length;
            
            const totalSize = allImages.reduce((sum, img) => sum + (img.size || 0), 0);
            document.getElementById('total-size').textContent = (totalSize / (1024 * 1024)).toFixed(2) + ' MB';
        }

        function displayGallery() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const sortBy = document.getElementById('sort-select').value;
            
            let filtered = allImages.filter(img => {
                return img.filename.toLowerCase().includes(searchTerm) ||
                       (img.date && img.date.toLowerCase().includes(searchTerm)) ||
                       (img.source && img.source.toLowerCase().includes(searchTerm));
            });

            // Sort
            filtered.sort((a, b) => {
                switch(sortBy) {
                    case 'newest': return b.timestamp - a.timestamp;
                    case 'oldest': return a.timestamp - b.timestamp;
                    case 'name': return a.filename.localeCompare(b.filename);
                    case 'size': return (b.size || 0) - (a.size || 0);
                    default: return 0;
                }
            });

            document.getElementById('filtered-images').textContent = filtered.length;

            const container = document.getElementById('gallery-container');
            
            if (filtered.length === 0) {
                showNoImages();
                return;
            }

            container.innerHTML = filtered.map((img, index) => `
                <div class="gallery-item" onclick="openModal(${index})">
                    <img src="${img.path}" alt="${img.filename}" loading="lazy">
                    <div class="gallery-info">
                        <h4><i class="fas fa-image"></i> ${img.filename}</h4>
                        <p><i class="fas fa-calendar"></i> ${img.date || 'Unknown'}</p>
                        <p><i class="fas fa-database"></i> ${formatSize(img.size || 0)}</p>
                        ${img.source ? `<p><i class="fas fa-link"></i> ${img.source}</p>` : ''}
                        <div class="gallery-actions">
                            <button class="btn btn-primary" onclick="event.stopPropagation(); downloadImage('${img.path}', '${img.filename}')">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button class="btn btn-danger" onclick="event.stopPropagation(); deleteImage('${img.filename}', ${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function showNoImages() {
            document.getElementById('gallery-container').innerHTML = `
                <div class="no-images">
                    <i class="fas fa-images"></i>
                    <h2>No Images Found</h2>
                    <p>No captured images available. Start a phishing campaign to capture images.</p>
                </div>
            `;
        }

        function openModal(index) {
            const img = allImages[index];
            document.getElementById('modal-image').src = img.path;
            document.getElementById('modal-info').innerHTML = `
                <h3><i class="fas fa-info-circle"></i> Image Details</h3>
                <p><strong>Filename:</strong> ${img.filename}</p>
                <p><strong>Date:</strong> ${img.date || 'Unknown'}</p>
                <p><strong>Size:</strong> ${formatSize(img.size || 0)}</p>
                ${img.source ? `<p><strong>Source:</strong> ${img.source}</p>` : ''}
                ${img.ip ? `<p><strong>IP:</strong> ${img.ip}</p>` : ''}
                ${img.userAgent ? `<p><strong>User Agent:</strong> ${img.userAgent}</p>` : ''}
            `;
            document.getElementById('imageModal').style.display = 'block';
        }

        function closeModal(event) {
            if (!event || event.target.id === 'imageModal' || event.target.className === 'modal-close') {
                document.getElementById('imageModal').style.display = 'none';
            }
        }

        function downloadImage(path, filename) {
            const a = document.createElement('a');
            a.href = path;
            a.download = filename;
            a.click();
        }

        function deleteImage(filename, index) {
            if (!confirm(`Delete ${filename}?`)) return;
            
            fetch('delete_image.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({filename: filename})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allImages.splice(index, 1);
                    displayGallery();
                    updateStats();
                } else {
                    alert('Failed to delete image');
                }
            });
        }

        function clearAll() {
            if (!confirm('Delete ALL images? This cannot be undone!')) return;
            
            fetch('clear_images.php', {method: 'POST'})
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allImages = [];
                        displayGallery();
                        updateStats();
                        alert('All images deleted');
                    }
                });
        }

        function formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(2) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        // Event listeners
        document.getElementById('search-input').addEventListener('input', displayGallery);
        document.getElementById('sort-select').addEventListener('change', displayGallery);

        // Load on start
        loadGallery();
    </script>
</body>
</html>
