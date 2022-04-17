<?php
namespace core\utils\crypto;

class Crypto {

  public static function encrypt($str) {
    $phrase = setting('--crypt.phrase'); // 32
    $iv = setting('--crypt.iv'); // 16

    return openssl_encrypt($str, 'AES-256-CBC', $phrase, 0, $iv);
  }

  public static function decrypt($str) {
    $phrase = setting('--crypt.phrase'); // 32
    $iv = setting('--crypt.iv'); // 16

    return openssl_decrypt($str, 'AES-256-CBC', $phrase, 0, $iv);
  }
}
