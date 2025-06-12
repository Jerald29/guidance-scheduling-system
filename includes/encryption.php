<?php
require 'conn.php';

// Encryption key and constants for AES-GCM
define('ENCRYPTION_KEY', '6b50d38b826b79e9d42207bd3b8939c43ebfa046ed3b918d3c9ba84e9e5d56f3');
define('IV_LENGTH', 12); 
define('TAG_LENGTH', 16);

// Encrypts plaintext using AES-256-GCM
function encrypt($plaintext) {
    $iv = openssl_random_pseudo_bytes(IV_LENGTH); // Generate IV
    $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv, $tag);
    if ($ciphertext === false) throw new Exception('Encryption failed.');
    return base64_encode($iv . $tag . $ciphertext); // Combine IV, tag, and ciphertext
}

// Decrypts ciphertext using AES-256-GCM
function decrypt($ciphertext) {
    $data = base64_decode($ciphertext); // Decode the data
    $iv = substr($data, 0, IV_LENGTH);
    $tag = substr($data, IV_LENGTH, TAG_LENGTH);
    $ciphertext = substr($data, IV_LENGTH + TAG_LENGTH);
    $plaintext = openssl_decrypt($ciphertext, 'aes-256-gcm', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv, $tag);
    if ($plaintext === false) throw new Exception('Decryption failed.');
    return $plaintext;
}
?>
