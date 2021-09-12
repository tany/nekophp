<?php
namespace core\logger;

use \core\error\ErrorCode;

class Log {

  public static $file;

  public static function initialize($file) {
    self::$file = $file;
  }

  public static function shutdown() {
    if ($e = error_get_last()) {
      $type = ErrorCode::TEXTS[$e['type']];
      log_warn("{$type}: {$e['message']} in {$e['file']} on line {$e['line']}");
    }
  }

  public static function log($data, $level) {
    static $pid;
    $pid ??= getmypid();

    $time = date('Y-m-d H:i:s');
    $data = preg_replace("/\n/", "\n  ", trim(self::export($data)));
    $data = sprintf('%s | %6s | %-5s | %s', $time, $pid, $level, $data);

    if (self::$file) error_log("{$data}\n", 3, self::$file);
  }

  protected static function export($data) {
    if (!$data || is_bool($data)) {
      return var_export($data, true);
    }
    // if (is_scalar($data) && strpos($data, "\n") !== false) {
    //   return print_r($data, true);
    // }
    return print_r($data, true);
  }
}
