<?php
namespace core\cache;

use \core\storage\Cache;

class APCu {

  public static function get($key, $callback = null) {
    $name = HASH . ".{$key}";
    $val = Cache::$keep ? apcu_fetch($name, $result) : null;

    if (empty($result)) $val = null;
    if ($val === null && $callback) return self::set($key, $callback());
    return $val;
  }

  public static function set($key, $val) {
    $name = HASH . ".{$key}";

    if (apcu_store($name, $val)) return $val;
    throw new \Exception("apcu_store(): APCu doesn't work");
  }
}
