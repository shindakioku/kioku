<?php

namespace Kioku\Http\Cookie;

final class Hash
{
    const PASSWORD = 'qow182138ek1239qiweoqwkle.qwek123';
    const CIPHER_METHOD = 'AES-256-CBC';

    /**
     * @param string $value
     * @return mixed
     */
    public static function encrypt($value)
    {
        $iv_length = \openssl_cipher_iv_length(self::CIPHER_METHOD);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $str = $iv.$value;
        $val = openssl_encrypt($str, self::CIPHER_METHOD, self::PASSWORD, 0, $iv);

        return str_replace(['+', '/', '='], ['_', '-', '.'], $val);
    }

    /**
     * @param string $value
     * @return string
     */
    public static function decrypt(string $value): string
    {
        $val = str_replace(['_', '-', '.'], ['+', '/', '='], $value);
        $data = base64_decode($val);
        $iv_length = openssl_cipher_iv_length(self::CIPHER_METHOD);
        $body_data = substr($data, $iv_length);
        $iv = substr($data, 0, $iv_length);
        $base64_body_data = base64_encode($body_data);

        return openssl_decrypt($base64_body_data, self::CIPHER_METHOD, self::PASSWORD, 0, $iv);
    }
}