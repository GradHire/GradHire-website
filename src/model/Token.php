<?php

namespace app\src\model;

class Token
{
    public static function generate($data, $expiration = 3600): string
    {
        $data['exp'] = $expiration + time();
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($data)));
        $signature = hash_hmac('sha256', "$header.$payload", jwt_key, true);
        $signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        return "$header.$payload.$signature";
    }

    public static function verify($token)
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3)
            return null;

        list($header, $payload, $signature) = $parts;

        $payloadData = json_decode(base64_decode($payload), true);
        $expectedSignature = hash_hmac('sha256', "$header.$payload", jwt_key, true);
        $expectedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));

        if ($signature === $expectedSignature && isset($payloadData['exp']) && $payloadData['exp'] >= time())
            return $payloadData;

        return null;
    }
}