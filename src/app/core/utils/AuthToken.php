<?php
namespace core\utils;

use \core\storage\Cookie;
use \core\storage\Session;
use \core\utils\crypto\Crypto;
use \core\models\User;

class AuthToken {

  protected static function hashUserAgent() {
    return substr(sha1(SERVER['HTTP_USER_AGENT'] ?? ''), 0, 7);
  }

  public static function authenticate() {
    if (!$token = Cookie::get('authToken')) return;

    $data = explode(';', Crypto::decrypt($token));
    if (count($data) !== 4) return self::logout();

    [$id, $time, $cip, $cua] = $data;
    $match = ($cip === REAL_IP) + ($cua === self::hashUserAgent());
    if ($match === 0) return self::logout();

    $user = User::find($id);
    if (!$user || !self::verify($user)) return self::logout();

    if ($match === 1 || $time + 86_400 < TIME) return self::login($user);

    return $user;
  }

  public static function verify($user) {
    if ($user->name === '') return false;

    // verify

    return true;
  }

  public static function login($user) {
    $token = join(';', [$user->id, TIME, REAL_IP, self::hashUserAgent()]);
    Cookie::set('authToken', Crypto::encrypt($token));
    return $user;
  }

  public static function logout() {
    Cookie::delete('authToken');
    Session::destroy();
  }
}
