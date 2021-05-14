<?php
namespace core\actions;

use \core\Core;
use \core\utils\AuthToken;

trait LoginFilter {

  // protected $loginRequired = true;

  protected function loginBefore($request) {
    if (Core::$user) return;

    Core::$user = AuthToken::authenticate();
    if (Core::$user) return;

    $required = $this->loginRequired ?? true;
    return $required ? $this->locate('/login') : null;
  }
}
