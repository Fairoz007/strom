<?php
$result_file = "result.txt";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $fingerprint = json_decode($data, true);
    
    if ($fingerprint) {
        $timestamp = date('Y-m-d H:i:s');
        $log = "=== Device Fingerprint ===\n";
        $log .= "Timestamp: $timestamp\n\n";
        
        $log .= "BROWSER INFORMATION:\n";
        if (isset($fingerprint['browser'])) {
            foreach ($fingerprint['browser'] as $key => $value) {
                $log .= ucfirst($key) . ": " . (is_array($value) ? json_encode($value) : $value) . "\n";
            }
        }
        
        $log .= "\nDEVICE INFORMATION:\n";
        if (isset($fingerprint['device'])) {
            foreach ($fingerprint['device'] as $key => $value) {
                $log .= ucfirst($key) . ": " . $value . "\n";
            }
        }
        
        $log .= "\nNETWORK INFORMATION:\n";
        if (isset($fingerprint['network'])) {
            foreach ($fingerprint['network'] as $key => $value) {
                $log .= ucfirst($key) . ": " . $value . "\n";
            }
        }
        
        $log .= "\nCAPABILITIES:\n";
        if (isset($fingerprint['capabilities'])) {
            foreach ($fingerprint['capabilities'] as $key => $value) {
                $log .= ucfirst($key) . ": " . ($value ? 'Yes' : 'No') . "\n";
            }
        }
        
        $log .= "\nSECURITY INFO:\n";
        if (isset($fingerprint['security'])) {
            foreach ($fingerprint['security'] as $key => $value) {
                if (!is_array($value)) {
                    $log .= ucfirst($key) . ": " . $value . "\n";
                }
            }
        }
        
        $log .= "\n" . str_repeat("=", 50) . "\n\n";
        
        file_put_contents($result_file, $log, FILE_APPEND);
    }
}
?>
