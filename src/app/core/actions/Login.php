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
    $this->redirect = 1;
    if ($request->isPost()) return $this->login($request);
  }

  protected function login($request) {
    $data = $request->data;
    $user = User::findByName($data['username']);
    //(new User)->authenticate($data['username'] ?? null, $data['password'] ?? null);

    Core::$user = $user ? AuthToken::login($user) : null;

    if (!Core::$user) {
      return $this->fail(['status' => 401, 'title' => lc('core.alert.login.fail')]);
    }

    if (empty($data['redirect'])) {
      $this->done(['reflesh' => true, 'alert' => lc('core.alert.login.done')]);
    } else {
      $this->done(['location' => '/', 'alert' => lc('core.alert.login.done')]);
    }
  }

  public function logout($request) {
    AuthToken::logout();

    $this->done(['location' => '/login', 'alert' => lc('core.alert.logout.done')]);
  }
}
