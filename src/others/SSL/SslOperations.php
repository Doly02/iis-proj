<?php

namespace Others\SSL;

final class SslOperations
{
    public function encryptAccountType(string $accountType): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($accountType, 'aes-256-cbc', SslEnum::HASH_K, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decryptAccountType(string $encryptedAccountType): string
    {
        $data = base64_decode($encryptedAccountType);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, 'aes-256-cbc', SslEnum::HASH_K, 0, $iv);
    }
}