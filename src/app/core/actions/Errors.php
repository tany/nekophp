<?php
namespace core\actions;

use \core\utils\ResponseCode;

class Errors extends \core\Action {

  use \core\actions\LoginFilter;

  public $code = null;
  protected $loginRequired = false;

  public function index($request) {
    $this->code = (int)$request->code;
    $this->text = ResponseCode::TEXTS[$this->code] ?? '';

    if ($this->code === 404 && MODE === 'development' && $request->_routes) {
      return $this->showRoutes();
    }
    return $this->render();
  }

  protected function showRoutes() {
    $routes = [];
    $length = [[], []];
    foreach (\core\Router::$routes as $items) {
      foreach ($items as $key => $item) {
        $routes[] = $route = [trim($item[3] ?? $key, '|'), "{$item[0]}#{$item[1]}"];
        $length[0][] = strlen($route[0]);
        $length[1][] = strlen($route[1]);
      }
    }

    $keyMax = max($length[0]);
    $valMax = max($length[1]);
    print sprintf("%-{$keyMax}s | %s\n", 'Path', 'Action');
    print sprintf("%s-+-%s\n", str_repeat('-', $keyMax), str_repeat('-', $valMax));
    foreach ($routes as [$path, $action]) {
      print sprintf("%-{$keyMax}s | %s\n", $path, $action);
    }
    return $this->response->sendData(ob_capture())->setContentType('text/plain');
  }
}
