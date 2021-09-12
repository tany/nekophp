<?php
namespace core;

use \core\config\Locale;
use \core\config\Setting;
use \core\error\ErrorHandler;
use \core\logger\Dump;
use \core\logger\Log;
use \core\storage\Cache;
use \core\storage\Session;

class Core {

  public static $request;
  public static $lang;
  public static $user;
  public static $site;

  public static function initialize() {
    class_alias(self::class, 'Core'); // for view

    define('ENV', $_ENV); //filter_input_array(INPUT_ENV));
    define('SERVER', $_SERVER); //filter_input_array(INPUT_SERVER));
    define('TIME', SERVER['REQUEST_TIME']);
    define('TIME_FLOAT', SERVER['REQUEST_TIME_FLOAT']);
    define('REAL_IP', SERVER['HTTP_X_REAL_IP'] ?? SERVER['REMOTE_ADDR'] ?? null);
    define('ACCEPT', str_pop(SERVER['HTTP_ACCEPT'] ?? '', ',', ';'));
    define('HASH', substr(sha1(basename(ROOT)), 0, 7));

    ob_start();
    set_error_handler([ErrorHandler::class, 'error']);
    set_exception_handler([ErrorHandler::class, 'except']);
    register_shutdown_function([self::class, 'shutdown']);

    $conf = Setting::initialize();
    Cache::initialize($conf);

    define('MODE', $conf['core.mode']);
    define('LANG', $conf['core.lang']);
    define('APPS', Cache::get('core.apps', fn() => ls_dir(APP)));

    Setting::$data = Setting::load();
    Log::initialize($conf['core.log.file']);
    Locale::initialize($conf['core.lang']);
    Session::initialize('s' . HASH);
  }

  public static function shutdown() {
    Cache::shutdown();
    Log::shutdown();
    Dump::shutdown();
  }

  /**
   * Usage:
   *   register_tick_function([self::class, 'tick']);
   *   declare(ticks=1);
   */
  public static function tick() {
    static $time;
    $time ??= TIME_FLOAT;

    $now = microtime(true);
    $elapsed = number_sigfig(($now - $time) * 1000);
    $time = $now;

    $trace = debug_backtrace()[1] ?? debug_backtrace()[0];
    $logs[] = "{$elapsed} ms";
    $logs[] = ($trace['class'] ?? null) . "#{$trace['function']}";
    $logs[] = "{$trace['file']}:{$trace['line']}";

    log_debug(join(' - ', $logs));
  }
}
