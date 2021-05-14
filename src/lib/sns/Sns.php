<?php
namespace sns;

class Core {

  public static $target;

  public static function initialize() {
    class_alias(self::class, 'Sns');
  }
}
