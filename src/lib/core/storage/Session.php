<?php
namespace core\storage;

use \core\storage\Cookie;

class Session {

  protected static $started;

  public static $expires = 3_600;
  public static $name;

  public static function initialize($name) {
    self::$name = $name;
  }

  public static function start() {
    if (self::$started) return;
    self::$started = true;

    if (session_status() === PHP_SESSION_ACTIVE) return;
    if (empty(SERVER['HTTPS'])) session_set_cookie_params(['secure' => 0]);

    session_name(self::$name);
    session_start();

    $created = ($_SESSION['_created'] ??= TIME);
    if (!is_numeric($created) || $created + self::$expires < TIME) self::regenerate();
  }

  public static function regenerate() {
    session_regenerate_id(true);
    $_SESSION['_created'] = TIME;
  }

  public static function get($key) {
    if (!self::$started) self::start();
    return $_SESSION[$key] ?? null;
  }

  public static function set($key, $val) {
    if (!self::$started) self::start();
    return $_SESSION[$key] = $val;
  }

  public static function delete($key) {
    if (!self::$started) self::start();
    unset($_SESSION[$key]);
  }

  public static function data() {
    if (!self::$started) self::start();
    return $_SESSION;
  }

  public static function unset() {
    if (!self::$started) self::start();
    $_SESSION = [];
  }

  public static function destroy() {
    if (!Cookie::get(self::$name)) return;
    if (!self::$started) self::start();

    $_SESSION = [];
    session_destroy();
    Cookie::delete(self::$name);
    self::$started = null;
  }
}
