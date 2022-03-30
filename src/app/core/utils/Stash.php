<?php
namespace core\utils;

class Stash {

  protected $pin;
  protected $str;

  public function __construct($pin = null) {
    $this->pin = $pin ?? hex2bin('c2b4efbda5cf89efbda560') . uniqid() . ';';
  }

  public function stash($pattern, $str) {
    preg_match_all($pattern, $str, $m, PREG_OFFSET_CAPTURE);

    if ($this->str = $m[0]) {
      $pin = $this->pin;
      $rev = array_reverse($m[0]);
      foreach ($rev as $c) {
        $str = substr_replace($str, $pin, $c[1], strlen($c[0]));
      }
    }
    return $str;
  }

  public function restore($str) {
    $pin = $this->pin;
    $pattern = preg_quote($pin, '/');

    if ($this->str && preg_match_all("/{$pattern}/", $str, $m, PREG_OFFSET_CAPTURE)) {
      $tmp = $this->str;
      $len = strlen($pin);
      $rev = array_reverse($m[0], true);
      foreach ($rev as $k => $c) {
        $str = substr_replace($str, $tmp[$k], $c[1], $len);
      }
    }
    return $str;
  }
}
