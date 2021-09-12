<?php
namespace core;

class Loader {

  public static function initialize($root) {
    define('ROOT', $root);
    define('CNF', "{$root}/conf");
    define('LOG', "{$root}/log");
    define('SRC', "{$root}/src");
    define('APP', "{$root}/src/app");
    define('LIB', "{$root}/src/lib");
    define('TMP', "{$root}/tmp");
    define('WEB', "{$root}/web");

    spl_autoload_register([self::class, 'loadClass']);
    require ROOT . '/vendor/autoload.php';
  }

  public static function include(...$paths) {
    set_include_path(get_include_path() . PATH_SEPARATOR . join(PATH_SEPARATOR, $paths));
  }

  public static function loadClass($class) {
    // return require strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';
    $file = strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';
    if ($path = stream_resolve_include_path($file)) require $path;
  }
}
