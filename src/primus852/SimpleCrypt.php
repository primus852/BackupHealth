<?php

namespace primus852;

use primus852\Config;


class SimpleCrypt
{


    public function encrypt($string)
    {

        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', Config::SC_KEY);
        $iv = substr(hash('sha256', Config::SC_IV), 0, 16);

        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));

        return $output;
    }

    public function decrypt($string)
    {

        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', Config::SC_KEY);
        $iv = substr(hash('sha256', Config::SC_IV), 0, 16);

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

        return $output;
    }


}