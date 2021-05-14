<?php
namespace core\error;

use \core\error\ErrorCode;

class ErrorReport {

  public static $uncompiledFile;

  protected $type;
  protected $title;
  protected $views;

  public static function setUncompiledFile($file) {
    self::$uncompiledFile ??= $file;
  }

  public function __construct($e) {
    while (ob_get_level()) ob_get_clean();
    ob_start();

    $this->type = ErrorCode::TEXTS[$e->getCode()] ?? '?';
    $this->title = self::correctTitle($e);

    $file = self::$uncompiledFile;
    if ($file && is_file($file)) $this->appendView($e->getFile(), $e->getLine(), $file);

    $file = preg_replace('/\(.*$/', '', $e->getFile());
    if ($file && is_file($file)) $this->appendView($e->getFile(), $e->getLine());

    $this->views[] = [
      'name' => 'Trace Log',
      'data' => $this->trace($e),
    ];
  }

  protected function appendView($file, $line, $uncompiledFile = null) {
    if (preg_match('/^(.*?)\((.*?)\).*eval/', $file, $m)) {
      $file = $m[1];
      $line = (int)$m[2];
    }
    $file = $uncompiledFile ?? $file;

    $this->views[] = [
      'name' => "{$file}:{$line}" . ($uncompiledFile ? '?' : ''),
      'data' => $this->highlight(file_read($file), $line),
    ];
  }

  protected static function correctTitle($e) {
    if (is_a($e, 'MongoDB\Driver\Exception\BulkWriteException')) {
      foreach ($e->getWriteResult()->getWriteErrors() as $err) {
        $msg[] = $err->getMessage();
      }
      return join('; ', $msg ?? []);
    }
    return $e->getMessage();
  }

  protected function highlight($source, $line) {
    $sline  = ($line - 5) > 0 ? $line - 5 : 0;
    foreach (array_slice(explode("\n", $source), $sline, 9) as $idx => $str) {
      $key = $sline + $idx + 1;
      $data[] = [
        'key' => $key,
        'val' => $str,
        'css' => ($key === $line ? 'current' : '')
      ];
    }
    return $data ?? [];
  }

  protected function trace($e) {
    foreach (explode("\n", $e->getTraceAsString()) as $str) {
      $data = explode(' ', $str, 2);
      $logs[] = ['key' => $data[0], 'val' => $data[1]];
    }
    return $logs;
  }

  public function render() {
    http_response_code(500);

    if (ACCEPT === 'application/json') {
      $data = ['error' => $this->title];
      if (MODE === 'development') {
        $data['items'] = [$this->views[0]['name']];
      }
      print json_encode_pretty($data);
    } elseif (ACCEPT === 'text/html') {
      require 'views/report_html.php';
    } else {
      require 'views/report_text.php';
    }
  }
}
