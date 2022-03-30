<?php
namespace core\config;

use \core\Core;
use \core\storage\Cache;

class Locale {

  public static $data;

  public static function initialize($lang) {
    Core::$lang = $lang;

    if (!isset(self::$data[$lang])) {
      self::$data[$lang] = self::load($lang);
    }
  }

  public static function load($lang) {
    if ($data = Cache::get("core.locales.{$lang}")) return $data;

    foreach (APPS as $mod) {
      $data[] = file_try_include(APP . "/{$mod}/@conf/lang/{$lang}.php") ?? [];
    }
    $data[] = file_try_include(CNF . "/lang/{$lang}.php") ?? [];

    return Cache::set("core.locales.{$lang}", array_merge(...$data));
  }

  public static function get($lang, $key, ...$params) {
    if (!$data = &self::$data[$lang][$key]) {
      $data ??= str_human(extname($key, ''));
      log_warn("Locale failed: {$key}");
    }
    return $params ? vsprintf($data, $params) : $data;
  }
}
