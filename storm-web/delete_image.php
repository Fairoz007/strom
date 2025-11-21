<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$filename = $data['filename'] ?? '';

if (empty($filename)) {
    echo json_encode(['success' => false, 'error' => 'No filename provided']);
    exit;
}

$filePath = './images/' . basename($filename);

if (file_exists($filePath) && is_file($filePath)) {
    if (unlink($filePath)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete file']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'File not found']);
}
?>
