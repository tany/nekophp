<?php
namespace core\router;

use \core\Router;
use \core\storage\Cache;

class Parser {

  public static function initialize() {
    return Cache::get('--routes', fn() => self::load());
  }

  public static function load() {
    foreach (APPS as $mod) {
      $data[] = file_try_load(APP . "/{$mod}/@conf/routes.php");
    }
    return self::parse(join("\n", $data ?? []));
  }

  protected static function parse($conf) {
    $routes = ['=' => [], '*' => []];
    $conf = preg_replace('/^\s*(#|\/\/).*\n/m', '', $conf);

    foreach (explode("\n", $conf) as $line) {
      if (!trim($line)) continue;
      if (!preg_match('/\A(.*?)\s+(.*?)(?:#(.*))?\z/', $line, $m)) continue;

      [$path, $class, $action] = [$m[1], $m[2], $m[3] ?? 'index'];

      if ($action === 'REST') {
        self::addRoute($routes, dirname($path) . '/', $class, 'REST/index');
        self::addRoute($routes, $path, $class, 'REST/show');
        continue;
      }
      self::addRoute($routes, $path, $class, $action);
    }
    return $routes;
  }

  protected static function addRoute(&$routes, $path, $class, $action) {
    $class = substr_replace($class, '/actions/', strpos($class, '/'), 1);
    $class = strtr($class, '/', '\\');
    $route = [$class, $action];

    if (str_contains($path, '*')) return self::addRegexRoute($routes, $path, $route, '*');
    if (str_contains($path, ':')) return self::addRegexRoute($routes, $path, $route, substr_count($path, '/'));

    $routes['='][$path] = $route;
  }

  protected static function addRegexRoute(&$routes, $path, $route, $index) {
    $regex = str_replace('.', '\.', $path);
    $regex = preg_replace('/:\w+/', '([^/]+)', $regex);
    $regex = preg_replace('/\*\w+/', '(.*)', $regex);
    $regex = "|^{$regex}$|";

    if (preg_match_all('/[:\*]+(\w+)/', $path, $params)) $route[] = $params[1];

    $routes[$index][$regex] = $route;
  }

  public static function match($path, $request, &$routes) {
    if ($route = $routes['='][$path] ?? null) {
      return $route;
    }
    if ($route = self::matchRegex($path, $request, $routes[substr_count($path, '/')] ?? [])) {
      return $route;
    }
    if ($route = self::matchRegex($path, $request, $routes['*'])) {
      return $route;
    }
  }

  protected static function matchRegex($path, $request, $routes) {
    foreach ($routes as $regex => $route) {
      if (preg_match($regex, $path, $m)) {
        foreach ($route[2] ?? [] as $i => $n) {
          $request->$n = rawurldecode($m[$i + 1]);
        }
        return $route;
      }
    }
  }
}
