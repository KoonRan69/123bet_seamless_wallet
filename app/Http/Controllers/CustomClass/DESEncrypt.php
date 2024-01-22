<?php
namespace App\Http\Controllers\CustomClass;


class DESEncrypt
{
    var $key;
    var $iv;
    function __construct( $key, $iv=0 ) {

        $this->key = $key;
        if( $iv == 0 ) {
            $this->iv = $key;
        } else {
            $this->iv = $iv;
        }
    }

    function encrypt($str) {

        return base64_encode( openssl_encrypt($str, 'DES-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv  ) );
    }
    function decrypt($str) {
        $str = openssl_decrypt(base64_decode($str), 'DES-CBC', $this->key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $this->iv);
        return rtrim($str, "\x1\x2\x3\x4\x5\x6\x7\x8");
    }

    function pkcs5Pad($text, $blocksize) {
        $pad = $blocksize - (strlen ( $text ) % $blocksize);
        return $text . str_repeat ( chr ( $pad ), $pad );
    }
}
?>