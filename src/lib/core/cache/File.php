<?php
namespace core\cache;

use \core\storage\Cache;

class File {

  public static function get($key, $callback = null) {
    $file = Cache::$path . "/files/{$key}";
    $data = (Cache::$keep && is_file($file)) ? unserialize(file_read($file)) : null;

    if ($data === null && $callback) file_write($file, $data = $callback());
    return $data;
  }

  public static function set($key, $data) {
    $file = Cache::$path . "/files/{$key}";
    return file_write($file, $data);
  }
}
