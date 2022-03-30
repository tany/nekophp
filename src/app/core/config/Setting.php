<?php
namespace core\config;

use \core\storage\Cache;

class Setting {

  public static $data = [];

  public static function initialize() {
    self::$data += file_try_include(CNF . '/settings.php') ?? [];
    self::$data += file_try_include(APP . '/core/@conf/settings.php') ?? [];

    return self::$data;
  }

  public static function load() {
    if ($data = Cache::get('--settings')) return $data;

    $data = self::$data;
    foreach (APPS as $mod) {
      $data += file_try_include(APP . "/{$mod}/@conf/settings.php") ?? [];
    }

    return Cache::set('--settings', $data);
  }

  public static function get($key) {
    return self::$data[$key];
  }
}
