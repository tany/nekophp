<?php
namespace core\utils;

class Crypt {

  public static function encrypt($str) {
    $phrase = setting('core.crypt.phrase'); // 32
    $iv = setting('core.crypt.iv'); // 16

    return openssl_encrypt($str, 'AES-256-CBC', $phrase, 0, $iv);
  }

  public static function decrypt($str) {
    $phrase = setting('core.crypt.phrase'); // 32
    $iv = setting('core.crypt.iv'); // 16

    return openssl_decrypt($str, 'AES-256-CBC', $phrase, 0, $iv);
  }
}
