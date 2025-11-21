<?php
header('Content-Type: application/json');

$imagesDir = './images/';
$deleted = 0;
$errors = [];

if (is_dir($imagesDir)) {
    $files = scandir($imagesDir);
    
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && $file !== 'image.log') {
            $filePath = $imagesDir . $file;
            
            if (is_file($filePath)) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                    if (unlink($filePath)) {
                        $deleted++;
                    } else {
                        $errors[] = $file;
                    }
                }
            }
        }
    }
}

echo json_encode([
    'success' => count($errors) === 0,
    'deleted' => $deleted,
    'errors' => $errors
]);
?>
