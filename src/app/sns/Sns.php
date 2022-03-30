<?php
namespace sns;

class Sns {

  public static $target;

  public static function initialize() {
    class_alias(self::class, 'Sns');
  }
}
