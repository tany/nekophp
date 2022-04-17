<?php
namespace core;

use \core\Response;
use \core\storage\Cookie;
use \core\view\Engine;

class Action {

  use \core\features\Accessor;

  public static $overview = '--overview.default';

  public $request;
  public $action;
  public $response;

  public function __invoke($request, $action) {
    $this->request  = $request;
    $this->action   = $action;
    $this->response = new Response($this);
    $response = $this->response;

    $this->before($request);
    if ($response->isReady()) return $response;

    $this->{$this->action}($request);
    if (!$response->isReady()) $this->response->render();

    $this->after($request);
    return $response;
  }

  protected function before($request) {
    foreach (get_class_methods($this) as $method) {
      if (!str_ends_with($method, 'Before')) continue;
      $this->$method($request);
      if ($this->response->isReady()) return;
    }
  }

  protected function after($request) {
    foreach (get_class_methods($this) as $method) {
      if (!str_ends_with($method, 'After')) continue;
      $this->$method($request);
      if ($this->response->isReady()) return;
    }
  }

  public function alert($msg, $type = 'primary') {
    if (preg_match('/^\w+\.\w+/', $msg)) $msg = lc($msg);
    Cookie::set("alert__{$type}", $msg, ['expires' => 0]);
    return $this->response;
  }

  public function done($params = []) {
    $params += ['result' => 'done', 'status' => 200];
    $response = $this->response->json($params);

    return match ($params['status']) {
      201, 202 => $response->status($params['status']),
      default => $response->status(200)
    };
  }

  public function fail($params = []) {
    $params += ['result' => 'fail', 'status' => 400];
    $response = $this->response->json($params);

    return $response->status($params['status']);
  }
}
