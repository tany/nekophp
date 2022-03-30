<?php
namespace core\action;

use \core\utils\ResponseCode;

class Errors extends \core\Action {

  use \core\action\LoginFilter;

  public $code = null;

  protected $loginRequired = false;

  public function index($request) {
    $this->code = (int)$request->code;
    $this->text = ResponseCode::TEXTS[$this->code] ?? '';

    if ($this->code === 404 && MODE === 'development' && $request->_routes) {
      $this->showRoutes();
    }
  }

  protected function showRoutes() {
    $items = [];
    foreach (\core\Router::$routes as $routes) {
      foreach ($routes as $key => $val) {
        $items[$val[3] ?? $key] = "{$val[0]}#{$val[1]}";
      }
    }
    ksort($items);
    $this->items = $items;

    $this->response->render('routes');
  }
}
