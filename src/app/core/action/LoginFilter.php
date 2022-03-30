<?php
namespace core\action;

use \core\Core;
use \core\utils\AuthToken;

trait LoginFilter {

  // protected $loginRequired = true;

  protected function loginBefore($request) {
    if (Core::$user) return;

    Core::$user = AuthToken::authenticate();
    if (Core::$user) return;

    $required = $this->loginRequired ?? true;
    if ($required) $this->response->render('core/@views/login/index');
  }
}
