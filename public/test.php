<?php
class DES
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
	    echo $this->iv;exit;
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



$date = date('YmdHis', time());
$language = "en_US";
$username = "quoctestuser";




// ---- Integration environment ----

// Your own secret key
$secretkey = "123";

// Change the API url if production url is used
$url = "http://sai-api.sa-apisvr.com/api/api.aspx";

// MD5 Signature key
$md5key = "GgaIMaiNNtg";

// Encryption key
$key = "g9G16nTs";

// Change for your currency if necessary
$currency = "USD";

// Your Lobby code, please refer to integration email
$lobbyCode = "Axxx";

// Mobile mode?
$mobile = "false";

// Client loader
$loaderURL = "https://www.sai.slgaming.net/app.aspx";


$QS = "method=RegUserInfo&Key=".$secretkey."&Time=".$date."&Username=".$username."&CurrencyType=".$currency;

$s = md5($QS.$md5key.$date.$secretkey);


$crypt = new DES($key);
$q = $crypt->encrypt($QS);

echo $q;exit;
?>