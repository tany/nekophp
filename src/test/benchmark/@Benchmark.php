<?php
namespace benchmark;

/**
 * Benchmark
 *
 * Usage: `php -f /path/to/Syntax.php`
 *
 */
class Benchmark {

  protected static $times = 1000000;
  protected static $length = 30;
  protected static $caption;
  protected static $target;

  public static function initialize() {
    error_reporting(E_ALL);
    set_error_handler([self::class, 'error']);
    set_exception_handler([self::class, 'except']);

    self::$target ??= $_SERVER['argv'][1] ?? null;
    print_r(self::$target);
  }

  public static function h1($name, $times = null, $length = null) {
    self::initialize();

    print "# {$name} (PHP " . PHP_VERSION . ")\n";

    if ($times)  self::$times  = $times;
    if ($length) self::$length = $length;
  }

  public static function h2($name, $times = null, $length = null) {
    self::$caption = $name;
    if (self::$target && self::$target !== self::$caption) return;

    print "\n# {$name}\n";

    if ($times)  self::$times  = $times;
    if ($length) self::$length = $length;
  }

  public static function h3($name) {
    if (self::$target && self::$target !== self::$caption) return;

    print "\n  # {$name}\n";
  }

  public static function measure($name, $formula) {
    if (self::$target && self::$target !== self::$caption) return;

    $start = microtime(true);
    for ($i = 0; $i < self::$times; $i++) $formula();
    $elapsed = number_format((microtime(true) - $start) * 1_000, 3);

    $length = self::$length;
    print sprintf("    %-{$length}s : %10s ms\n", $name, $elapsed);
  }

  public static function error($code, $message, $file, $line) {
    self::except(new \ErrorException($message, $code, E_ERROR, $file, $line));
  }

  public static function except($e) {
    $message = $e->getMessage();
    $file = $e->getFile();
    $line = $e->getLine();
    print "\nError: {$message}\n\n  in {$file} on line {$line}\n\n";
    exit;
  }
}
