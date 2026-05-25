<?php

namespace App\Database;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class CustomEncryptor
{
    public static function deterministicEncrypt($value)
    {
        $key = config('app.key');
        $cipher = 'AES-256-ECB';

        $customSecretKey = env('CUSTOM_SECRET_KEY');
        $dataToEncrypt = $value . '::' . base64_encode($customSecretKey);

        $dataEncrypt = openssl_encrypt($dataToEncrypt, $cipher, $key);

        return $dataEncrypt;
    }

    public static function deterministicDecrypt($encryptedValue)
    {
        $key = config('app.key');
        $cipher = 'AES-256-ECB';

        $decrypted = openssl_decrypt($encryptedValue, $cipher, $key);
        
        if ($decrypted === false || !str_contains($decrypted, '::')) {
            return $encryptedValue;
        }

        list($originalData, $encodedSecretKey) = explode('::', $decrypted);
        $customSecretKey = env('CUSTOM_SECRET_KEY');
        $decodedSecretKey = base64_decode($encodedSecretKey);

        if ($customSecretKey === $decodedSecretKey) {
            $dataDecrypt = $originalData;
        }
        else {
            $dataDecrypt = $encryptedValue;
        }

        return $dataDecrypt;
    }
}
