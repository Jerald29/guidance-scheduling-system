<?php
function generate_encryption_key($length = 32) {
    // Ensure the length is valid
    if ($length !== 32) {
        throw new InvalidArgumentException("Key length must be 32 bytes.");
    }

    // Generate a random key
    return bin2hex(random_bytes($length)); // Converts the bytes to a hexadecimal string
}

// Generate the key
$key = generate_encryption_key();
echo "Generated Encryption Key: " . $key . "\n";
?>
