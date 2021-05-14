<?php
namespace core\storage;

use \core\Core;

class Cookie {

  public static function get($key) {
    if (!$key) throw new \Exception(__METHOD__ . '(): expects parameter 1 to be string.');

    if (str_ends_with($key, '*')) {
      $key = substr($key, 0, -1);
      foreach ($_COOKIE as $ckey => $cval) {
        if (!str_starts_with($ckey, $key)) continue;
        if (is_string($cval)) $val[substr($ckey, strlen($key))] = $cval;
      }
      return $val ?? [];
    }
    return is_string($_COOKIE[$key] ?? null) ? $_COOKIE[$key] : null;
  }

  public static function set($key, $val, $options = []) {
    if (!$key) throw new \Exception(__METHOD__ . '(): expects parameter 1 to be string.');

    setcookie($key, $val, self::correctOptions($options));
    $_COOKIE[$key] = $val;
    return $val;
  }

  public static function delete($key, $options = []) {
    $options['expires'] ??= -1;
    $optios = self::correctOptions($options);

    if (str_ends_with($key, '*')) return self::deleteList($key, $options);

    setcookie($key, null, $optios);
    unset($_COOKIE[$key]);
  }

  protected static function deleteList($key, $options) {
    $key = substr($key, 0, -1);
    foreach (array_keys($_COOKIE) as $ckey) {
      if (!str_starts_with($ckey, $key)) continue;

      setcookie($ckey, null, $options);
      unset($_COOKIE[$ckey]);
    }
  }

  public static function flash($key, $options = []) {
    $val = self::get($key);
    self::delete($key, self::correctOptions($options));
    return $val;
  }

  protected static function correctOptions($options) {
    $options['expires']  ??= TIME + 86_400 * 15;
    $options['path']     ??= '/';
    $options['domain']   ??= '';
    $options['secure']   ??= true;
    $options['httponly'] ??= true;

    if (empty(SERVER['HTTPS'])) {
      $options['secure'] = false;
    }
    return $options;
  }
}
