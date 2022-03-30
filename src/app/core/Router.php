<?php
namespace core;

use \core\Core;
use \core\error\ErrorResponse;
use \core\router\Parser;
use \core\router\Rewrite;

class Router {

  public static $routes = [];

  public static function initialize() {
    require APP . '/core/@conf/boot.php';
    foreach (APPS as $mod) {
      if ($mod !== 'core') file_try_include(APP . "/{$mod}/@conf/boot.php");
    }
    self::$routes = Parser::initialize();
  }

  public static function process($request) {
    $path = Rewrite::rewritePath($request);

    try {
      $resp = self::callAction($path, $request);
    } catch (ErrorResponse $e) {
      $resp = self::callErrorAction("@error/{$e->code}", $request);
    }
    return $resp->flash();
  }

  public static function callAction($path, $request) {
    $route = Parser::match($path, $request, self::$routes) ?? abort(404);
    $class = new $route[0]($request);
    $action = Rewrite::rewriteAction($request, $route[1]) ?? abort(404);
    if (!is_callable([$class, $action])) abort(404);

    $lastRequest = Core::$request;
    Core::$request = $request;

    $resp = $class->__invoke($request, $action);
    Core::$request = $lastRequest;

    return $resp;
  }

  public static function callErrorAction($path, $request) {
    $route = Parser::match($path, $request, self::$routes) ?? abort(404);
    $class = new $route[0]($request);
    $action = $route[1];
    if (!is_callable([$class, $action])) abort(404);

    return $class->__invoke($request, $action);
  }
}
