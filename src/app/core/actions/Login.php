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
    //(new User)->authenticate($data['username'] ?? null, $data['password'] ?? null);

    Core::$user = $user ? AuthToken::login($user) : null;

    if (!Core::$user) {
      return $this->fail(401, lc('core.alert.login.fail'));
    }

    $path = $data['path'] ?? '/';
    if ($path[0] !== '/' || $path === '/login') $path = '/';

    $this->done($path, 200, lc('core.alert.login.done'));
  }

  public function logout($request) {
    AuthToken::logout();

    //$this->done('/login', 200, lc('core.alert.logout.done'));
    $this->alert(lc('core.alert.logout.done'))->location('/login', 200);
  }
}
