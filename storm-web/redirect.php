<?php
// URL Encryption and Redirection Handler
session_start();

// Encryption key (change this to your own secret key)
define('ENCRYPTION_KEY', 'StormBreaker2025SecretKey!@#');

function encryptUrl($url) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($url, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptUrl($encrypted) {
    $data = base64_decode($encrypted);
    list($encrypted_data, $iv) = explode('::', $data, 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
}

// Handle redirect
if (isset($_GET['id'])) {
    $encryptedId = $_GET['id'];
    $targetUrl = decryptUrl($encryptedId);
    
    if ($targetUrl) {
        // Log access if needed
        $logFile = 'log/access.log';
        $logData = date('Y-m-d H:i:s') . " | " . $_SERVER['REMOTE_ADDR'] . " | " . $targetUrl . "\n";
        @file_put_contents($logFile, $logData, FILE_APPEND);
        
        // Redirect to actual template
        header('Location: ' . $targetUrl);
        exit;
    }
}

// If no valid ID, show error or redirect to home
header('HTTP/1.0 404 Not Found');
echo '<!DOCTYPE html>
<html>
<head>
    <title>Page Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { color: #e74c3c; margin: 0 0 10px 0; }
        p { color: #666; }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404 - Page Not Found</h1>
        <p>The link you followed may be broken or expired.</p>
    </div>
</body>
</html>';
?>
