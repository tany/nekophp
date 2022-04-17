<?php
namespace core\actions;

use \core\Core;
use \core\utils\AuthToken;
use \core\models\User;

class Login extends \core\Action {

  use \core\actions\LoginFilter;

  protected function before($request) {
    // skip login
  }

  public function index($request) {
    $this->redirect = 1;
    if ($request->isPost()) return $this->login($request);
  }

  protected function login($request) {
    $data = $request->data;
    $user = User::findByName($data['username']);

    Core::$user = $user ? AuthToken::login($user) : null;

    if (!Core::$user) {
      return $this->fail(['status' => 401, 'title' => lc('--alert.login.fail')]);
    }

    if (empty($data['redirect'])) {
      $this->done(['reflesh' => true, 'alert' => lc('--alert.login.done')]);
    } else {
      $this->done(['location' => '/', 'alert' => lc('--alert.login.done')]);
    }
  }

  public function logout($request) {
    AuthToken::logout();

    $this->done(['location' => '/login', 'alert' => lc('--alert.logout.done')]);
  }
}
