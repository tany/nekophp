<?php
namespace core;

use \core\Response;
use \core\storage\Cookie;
use \core\view\Engine;

class Action {

  use \core\feature\Accessor;

  public static $views    = [];
  public static $overview = '/core/overviews/default/page';
  public static $partials = [
    'page-head' => '/core/overviews/default/page-head',
    'page-navi' => '/core/overviews/default/page-navi',
    'page-main' => '/core/overviews/default/page-main',
    'page-foot' => '/core/overviews/default/page-foot',
  ];

  public $request;
  public $action;
  public $response;
  public $view;
  public $error;

  public function __invoke($request, $action) {
    $this->request  = $request;
    $this->action   = $action;
    $this->response = new Response;

    if ($resp = $this->before($request)) return $resp;
    if (!$resp) $resp = $this->{$this->action}($request);
    if (!$resp) $resp = $this->render();
    $this->after($request);

    return $resp;
  }

  protected function before($request) {
    foreach (get_class_methods($this) as $method) {
      if (!str_ends_with($method, 'Before')) continue;
      if ($resp = $this->$method($request)) return $resp;
    }
  }

  protected function after($request) {
    foreach (get_class_methods($this) as $method) {
      if (!str_ends_with($method, 'After')) continue;
      if ($resp = $this->$method($request)) return $resp;
    }
  }

  // View

  protected function render($file = null) {
    $current = str_snake(strtr(static::class, '\\', '/'));
    $current = substr_replace($current, '/views', strpos($current, '/'), 8);
    $views = array_merge([$current], static::$views);

    $this->view ??= new Engine($this);
    $this->view->includePath($views)->render($file ?? $this->action);
    return static::$overview ? $this->renderOverview() : $this->response;
  }

  protected function renderOverview($file = null) {
    $partials = static::$partials;
    foreach (class_parents_sort(static::class) as $class) {
      $partials += $class::$partials;
    }

    $this->view ??= new Engine($this);
    $this->view->setPartials($partials)->renderOverview($file ?? static::$overview);
    return $this->response;
  }

  // Response

  protected function locate($url = null, $code = 302) {
    return $this->response->locate($url ?? $this->request->getPath(), $code);
  }

  public function alert($msg, $type = 'primary') {
    if (preg_match('/^\w+\.\w+/', $msg)) $msg = lc($msg);
    Cookie::set("alert__{$type}", $msg, ['expires' => 0]);
    return $this;
  }
}
