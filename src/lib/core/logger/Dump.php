<?php
namespace core\logger;

use \core\storage\Cache;

class Dump {

  protected static $logs = [];
  protected static $ids = [];
  protected static $tmp;

  public static function shutdown() {
    if (defined('MODE') && MODE === 'production') return self::log();
    if (!ob_get_level()) return self::log();

    $header = join("\n", headers_list());
    if (stripos($header, 'Content-Type:') !== false) return self::log();

    if (PHP_SAPI === 'cli') return self::renderCli();
    if (ACCEPT === 'text/html') return self::renderHtml();

    return self::log();
  }

  public static function store(...$values) {
    foreach ($values as $value) {
      self::$tmp = '';
      self::export($value);
      self::$logs[] = rtrim(self::$tmp);
    }
  }

  protected static function export($data, $depth = 0, $join = false) {
    $indent = str_repeat('  ', $depth + 1);
    if (!$join) self::$tmp .= str_repeat('  ', $depth);

    if (is_null($data)) {
      self::$tmp .= '<null>' . "\n";
    } elseif (is_bool($data)) {
      self::$tmp .= var_export($data, true) . ' <bool>' . "\n";
    } elseif (is_scalar($data)) {
      self::$tmp .= print_r($data, true) . ' <' . gettype($data) . '>' . "\n";
    } elseif (is_array($data)) {
      self::$tmp .= '<array>' . "\n";
      foreach ($data as $key => $val) {
        self::$tmp .= "{$indent}[{$key}] => ";
        self::export($val, $depth + 1, true);
      }
    } elseif (is_object($data)) {
      $id = spl_object_id($data);
      self::$tmp .= '<' . get_class($data) . "> #{$id}\n";

      if (isset(self::$ids[$id])) return;
      self::$ids[$id] = true;

      $object = new \ReflectionClass($data);
      foreach ($object->getProperties() as $key => $prop) {
        self::$tmp .= "{$indent}[{$prop->name}] => ";
        $prop->setAccessible(true);
        self::export($prop->getValue($data), $depth + 1, true);
      }
    } else {
      self::$tmp .= get_type($data) . "\n";
    }
  }

  protected static function elapsed() {
    $time = number_sigfig((microtime(true) - TIME_FLOAT) * 1000);
    $label = Cache::$keep ? ' / Cache' :'';
    return "{$time} ms{$label}";
  }

  protected static function log() {
    if (!self::$logs) return;
    foreach (self::$logs as $str) {
      log_debug($str);
    }
  }

  protected static function renderCli() {
    if (!self::$logs) return;
    $hr = str_repeat('-', 60) . "\n";
    print "\n";
    print $hr . join("\n", self::$logs) . "\n\n" . self::elapsed() . "\n" . $hr;
  }

  protected static function renderHtml() {
    $data = join("\n", self::$logs);
    foreach (explode("\n", $data) as $line) {
      $size[] = mb_strwidth($line);
    }

    $width = 8 * max($size ?? []);
    $height = 16 * count($size) + 15;
    $styles = self::style($width, $height);

    $h = '';
    if (strlen($data)) {
      $h .= '<textarea style="' . $styles[0] . '" spellcheck="false" readonly>' . "\n";
      $h .= h($data) . '</textarea>';
    }
    $h .= '<div style="' . $styles[1] . '">' . "\n";
    $h .= self::elapsed() . '</div>';

    $buf = ob_capture();
    $pos = strrpos($buf, '</body>') ?: strrpos($buf, '</html>') ?: 0;
    print substr_replace($buf, $h, $pos, 0);
  }

  protected static function style($width, $height) {
    $background = "
      position: fixed; z-index: 1080; left: 3px;
      background: rgba(30,34,38,.88);
      box-shadow: 1px 1px 5px rgba(0,0,0,.6);
      color: #c2c2c2; font: 12px/1.4 'Segoe UI',monospace;
    ";
    $styles[] = "
      display: block; {$background} bottom: 50px;
      width: min({$width}px, calc(100vw - 55px)); min-width: 200px;
      height: min({$height}px, calc(100vh - 70px)); min-height: 50px;
      padding: 5px 6px; border: 0;
      resize: none; outline:0 !important; -webkit-appearance:none;
      word-spacing: .25rem;
    ";
    $styles[] = "
      {$background} bottom: 20px;
      padding: 5px 6px;
    ";

    foreach ($styles as &$css) {
      $css = preg_replace('/(\n\s*)/m', '', $css);
      $css = str_replace(': ', ':', $css);
      $css = str_replace('; ', ';', $css);
    }
    return $styles;
  }
}
