<?php
namespace core\logger;

use \core\storage\Cache;

class Dump {

  protected static $logs = [];
  protected static $hr;

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
    foreach ($values as $data) {
      if (!$data || is_bool($data)) {
        $logs[] =  var_export($data, true);
      } elseif (is_scalar($data)) {
        $logs[] =  print_r($data, true);
      } else {
        $data = print_r($data, true);
        if (!mb_check_encoding($data, 'UTF-8')) $data = mb_convert_encoding($data, 'UTF-8');
        $data = preg_replace('/\n\s*\(/', ' (', $data);
        $data = preg_replace('/\)\n\n/', ")\n", $data);
        $logs[] = rtrim($data);
      }
    }
    self::$logs = array_merge(self::$logs, $logs ?? []);
  }

  protected static function elapsed() {
    $time = number_sigfig((microtime(true) - TIME_FLOAT) * 1000);
    $label = Cache::$keep ? ' / Cache' :'';
    return "{$time} ms{$label}";
  }

  protected static function log() {
    if (!self::$logs) return;
    $str = join("\n", self::$logs);
    log_debug((str_contains($str, "\n") ? "\n" : '') . $str);
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
      color: #bbb; font: 12px/1.4 'Segoe UI',monospace;
    ";
    $styles[] = "
      display: block; {$background} bottom: 50px;
      width: min({$width}px, calc(100vw - 55px)); min-width: 200px;
      height: min({$height}px, calc(100vh - 55px)); min-height: 50px;
      padding: 5px 6px; border: 0;
      resize: none; outline:0 !important; -webkit-appearance:none;
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
