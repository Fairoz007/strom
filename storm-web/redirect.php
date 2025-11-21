<?php
// URL Encryption and Redirection Handler
// NO SESSION OR LOGIN REQUIRED - This is for target users!

error_reporting(0); // Suppress errors for clean redirect

// Encryption key (must match generate_links.php)
define('ENCRYPTION_KEY', 'StormBreaker2025SecretKey!@#');

function decryptUrl($encrypted) {
    try {
        $data = base64_decode($encrypted);
        if ($data === false) return false;
        
        $parts = explode('::', $data, 2);
        if (count($parts) !== 2) return false;
        
        list($encrypted_data, $iv) = $parts;
        $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
        return $decrypted !== false ? $decrypted : false;
    } catch (Exception $e) {
        return false;
    }
}

// Handle redirect - THIS IS THE MAIN FUNCTION
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $encryptedId = urldecode($_GET['id']); // Decode URL encoding
    $targetUrl = decryptUrl($encryptedId);
    
    if ($targetUrl !== false && !empty($targetUrl)) {
        // Log successful access
        $logFile = __DIR__ . '/log/access.log';
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
        $logData = date('Y-m-d H:i:s') . " | " . $ip . " | " . $targetUrl . " | " . $userAgent . "\n";
        @file_put_contents($logFile, $logData, FILE_APPEND);
        
        // Extract path from full URL
        $parsedUrl = parse_url($targetUrl);
        
        if (isset($parsedUrl['path']) && !empty($parsedUrl['path'])) {
            $path = $parsedUrl['path'];
            
            // Clean the path (remove leading slash if duplicate)
            if (substr($path, 0, 1) === '/') {
                $path = substr($path, 1);
            }
            
            // Perform redirect
            header('HTTP/1.1 302 Found');
            header('Location: /' . $path);
            exit();
        }
    }
    
    // If decryption failed, log it for debugging
    $logFile = __DIR__ . '/log/failed_decrypt.log';
    $logData = date('Y-m-d H:i:s') . " | Failed: " . substr($encryptedId, 0, 50) . "...\n";
    @file_put_contents($logFile, $logData, FILE_APPEND);
}

// Show 404 error if no valid redirect
http_response_code(404);

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
