<?php

$encrypted = file_get_contents("./jwt_enc");
$encrypted = base64_decode($encrypted);
$privKey = file_get_contents("./privKey");
openssl_private_decrypt($encrypted, $decrypted, $privKey);
echo "\n" . $decrypted . "\n\n";
