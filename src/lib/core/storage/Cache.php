<?php
namespace core\storage;

use \core\cache\APCu;
use \core\error\ErrorHandler;

class Cache {

  public static $driver;
  public static $expires;
  public static $lock;
  public static $path;
  public static $time;
  public static $keep;

  public static function initialize($conf) {
    self::$driver = $conf['core.cache.driver'];
    self::$expires = $conf['core.cache.expires'];
    self::$lock = $conf['core.cache.lock'];
    self::$path = $conf['core.cache.path'];
    self::$time = is_file(self::$lock) ? filemtime(self::$lock) : 0;
    self::$keep = self::$time + self::$expires >= TIME;
  }

  public static function shutdown() {
    if (ErrorHandler::$error) {
      // file_try_remove($file);
    }
    if (!self::$keep || self::$expires < 10) {
      touch(self::$lock);
    }
  }

  public static function get($key, $callback = null) {
    if (!$key) throw new \Exception(__METHOD__ . '(): expects parameter 1 to be string.');

    return self::$driver::get($key, $callback);
  }

  public static function set($key, $val) {
    if (!$key) throw new \Exception(__METHOD__ . '(): expects parameter 1 to be string.');

    return self::$driver::set($key, $val);
  }
}
