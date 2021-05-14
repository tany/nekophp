<?php
namespace core\actions;

use \core\Core;
use \core\utils\AuthToken;
use \core\models\User;

class Login extends \core\Action {

  use \core\actions\LoginFilter;

  protected function before($request) {
    ;// skip login
  }

  public function index($request) {
    if ($request->isPost()) return $this->login($request);
  }

  protected function login($request) {
    $data = $request->data;

    $user = User::findByName($data['username']);

    Core::$user = $user ? AuthToken::login($user) : null;
    if (Core::$user) return $this->locate('/');

    return $this->alert('core.alert.login.failed', 'danger')->render();
  }

  public function logout($request) {
    AuthToken::logout();

    return $this->locate('/login');
  }
}
