<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait PaymentTrait
{
    private function getReference(): String
    {
        return rand(100000000,999999999);
    }

    private function getUuid(): String
    {
        return (string) Str::uuid();
    }

    private function getNonce(): String
    {
        if (function_exists('random_bytes')) {
            $nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $nonce = mt_rand();
        }
        return $nonce;
    }

    private function getSeed(): String
    {
        return date('c');
    }
}
