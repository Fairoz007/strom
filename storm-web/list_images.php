<?php
header('Content-Type: application/json');

$imagesDir = './images/';
$images = [];

if (is_dir($imagesDir)) {
    $files = scandir($imagesDir);
    
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && $file !== 'image.log') {
            $filePath = $imagesDir . $file;
            
            if (is_file($filePath)) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                    $images[] = [
                        'filename' => $file,
                        'path' => $filePath,
                        'size' => filesize($filePath),
                        'timestamp' => filemtime($filePath),
                        'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                        'source' => 'camera_capture'
                    ];
                }
            }
        }
    }
}

// Sort by timestamp descending (newest first)
usort($images, function($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
});

echo json_encode([
    'success' => true,
    'count' => count($images),
    'images' => $images
]);
?>
