<?php
namespace core\logger;

use \core\logger\Log;
use \core\storage\Cache;

class Dump {

  protected static $logs = [];
  protected static $ids = [];
  protected static $tmp;

  public static function shutdown() {
    if (PHP_SAPI === 'cli') return self::renderCli();

    if (defined('MODE') && MODE === 'production') return;
    if (!ob_get_level()) return;

    $header = join("\n", headers_list());
    if (stripos($header, 'Content-Type:') !== false) return;

    if (ACCEPT === 'text/html') return self::renderHtml();
  }

  public static function store(...$values) {
    foreach ($values as $value) {
      $data = Log::export($value);
      if (Log::$file) error_log("{$data}\n", 3, Log::$file);

      // $data = Log::log($value, 'DEBUG');
      self::$logs[] = $data;
    }
  }

  protected static function elapsed() {
    $time = number_sigfig((microtime(true) - TIME_FLOAT) * 1000);
    $label = Cache::$keep ? ' / Cache' :'';
    return "{$time} ms{$label}";
  }

  protected static function renderCli() {
    if (!self::$logs) return;
    print "\n+" . str_repeat('-', 14) . " dump\n";
    print join("\n", self::$logs) . "\n\n" . self::elapsed() . "\n";
    print str_repeat('-', 19) . '+' . "\n";
  }

  protected static function renderHtml() {
    $styles = self::styles();
    $data = join("\n", self::$logs);

    $h = '';
    if (strlen($data)) {
      $h .= '<div style="' . $styles[0] . '">' . h($data) . '</div>';
    }
    $h .= '<div style="' . $styles[1] . '">' . "\n";
    $h .= self::elapsed() . '</div>';

    $buf = ob_capture();
    $pos = strrpos($buf, '</body>') ?: strrpos($buf, '</html>') ?: 0;
    print substr_replace($buf, $h, $pos, 0);
  }

  protected static function styles() {
    $background = "
      position: fixed; z-index: 1080; left: 3px;
      background: rgba(0,0,0,.78);
      box-shadow: 1px 1px 5px rgba(0,0,0,.6);
      color: #c2c2c2;
      font: 12px/1.3 var(--bs-font-monospace);
    ";
    $styles[] = "
      display: block; {$background} bottom: 50px;
      overflow: auto;
      min-width: 200px; max-width: 80vw;
      max-height: calc(100vh - 120px);
      padding: 5px 6px;
      white-space: pre-wrap;
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
