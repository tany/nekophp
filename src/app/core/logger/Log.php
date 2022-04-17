<?php
namespace core\logger;

use \core\error\ErrorCode;

class Log {

  public static $file;

  protected static $ids;

  public static function initialize($file) {
    self::$file = $file ?? LOG . '/' . MODE . '.log';
  }

  public static function shutdown() {
    if ($e = error_get_last()) {
      $type = ErrorCode::TEXTS[$e['type']];
      log_warn("{$type}: {$e['message']} in {$e['file']} on line {$e['line']}");
    }
  }

  public static function clear() {
    file_put_contents(self::$file, '');
  }

  public static function read() {
    return file_get_contents(self::$file);
  }

  public static function log($data, $level) {
    static $pid;

    $pid ??= getmypid();
    $time = date('Y-m-d H:i:s');
    $data = self::export($data);
    $info = sprintf('%s | %6s | %-5s | ', $time, $pid, $level);

    if (self::$file) error_log("{$info}{$data}\n", 3, self::$file);
    return $data;
  }

  public static function export($data, $depth = 0, $nest = false) {
    static $logs = [];

    if (!$depth) $logs = [];
    if (!$nest) $logs[] = str_repeat('  ', $depth);
    $indent = str_repeat('  ', $depth + 1);

    if ($data === null) {
      $logs[] = '<null>' . "\n";
    } elseif ($data === true || $data === false) {
      $logs[] = var_export($data, true) . ' <bool>' . "\n";
    } elseif (is_string($data)) {
      $logs[] = print_r($data, true) . ' <str>' . "\n";
    } elseif (is_scalar($data)) {
      $logs[] = print_r($data, true) . ' <' . get_debug_type($data) . '>' . "\n";
    } elseif (is_array($data)) {
      $logs[] = '<array>' . "\n";
      foreach ($data as $key => $val) {
        $logs[] = "{$indent}{$key}: ";
        self::export($val, $depth + 1, true);
      }
    } elseif (is_object($data)) {
      $id = spl_object_id($data);
      $logs[] = '<' . $data::class . "> #{$id}\n";

      if (!isset(self::$ids[$id])) {
        self::$ids[$id] = true;
        $object = new \ReflectionClass($data);
        $props = $object->getProperties();
        foreach ($props as $key => $prop) {
          $logs[] = "{$indent}\"{$prop->name}\": ";
          $prop->setAccessible(true);
          self::export($prop->getValue($data), $depth + 1, true);
        }
        if (!$props) {
          foreach ($data as $key => $val) {
            $logs[] = "{$indent}\"{$key}\": ";
            self::export($val, $depth + 1, true);
          }
        }
      }
    } else {
      $logs[] = gettype($data) . "\n";
    }
    return rtrim(join($logs));
  }
}
