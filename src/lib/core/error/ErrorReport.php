<?php
namespace core\error;

use \core\error\ErrorCode;
use \core\logger\Log;

class ErrorReport {

  public static $uncompiledFile;

  protected $title;
  protected $message;
  protected $views;

  public function __construct($e) {
    while (ob_get_level()) ob_get_clean();
    ob_start();

    match (get_class($e)) {
      'Elasticsearch\Common\Exceptions\BadRequest400Exception' => $this->setElasticTitle($e),
      'MongoDB\Driver\Exception\BulkWriteException' => $this->setMongoTitle($e),
      default => $this->setDefaultTitle($e),
    };

    if (ACCEPT === 'application/json') {
      print json_encode_pretty(['title' => $this->title, 'message' => $this->message]);
    } elseif (ACCEPT === 'text/html') {
      $this->buildViews($e);
      require 'views/report_html.php';
    } else {
      print "\n{$this->title}: {$this->message}\n\n";
      print "#- {$e->getFile()}({$e->getLine()})\n";
      print preg_replace('/:.*/', '', $e->getTraceAsString()) . "\n";
    }
  }

  public static function setUncompiledFile($file) {
    self::$uncompiledFile ??= $file;
  }

  protected function setDefaultTitle($e) {
    $this->title = ErrorCode::TEXTS[$e->getCode()] ?? 'Script Error';
    $this->message = $e->getMessage();
    if (!$this->message) $this->message = 'Uncaught ' . get_class($e);
  }

  protected function setElasticTitle($e) {
    $json = json_decode($e->getMessage(), true);

    $this->title = '[elastic] ' . ($json['error']['type'] ?? 'exception');
    $this->message = $json['error']['reason'] ?? 'unknown reason';

    if ($caused = $json['error']['caused_by'] ?? null) {
      $this->message .= "\n\n- {$caused['type']}\n- {$caused['reason']}";
    }
    // http_response_code($json['status']);
  }

  protected function setMongoTitle($e) {
    foreach ($e->getWriteResult()->getWriteErrors() as $err) $msg[] = $err->getMessage();
    $this->title = 'MongoDB Error';
    $this->message = join('; ', $msg ?? []);
  }

  protected function buildViews($e) {
    $file = self::$uncompiledFile;
    if ($file && is_file($file)) $this->appendView($e->getFile(), $e->getLine(), $file);

    $file = preg_replace('/\(.*$/', '', $e->getFile());
    if ($file && is_file($file)) $this->appendView($e->getFile(), $e->getLine());

    $this->views[] = [
      'name' => 'Trace Log',
      'data' => $this->traceback($e),
    ];
    $this->views[] = [
      'name' => 'Application Log',
      'data' => $this->tailLog(),
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

  protected function traceback($e) {
    foreach (explode("\n", $e->getTraceAsString()) as $str) {
      $data = explode(' ', $str, 2);
      $logs[] = ['key' => $data[0], 'val' => $data[1]];
    }
    return $logs;
  }

  protected function tailLog() {
    if (!is_file($file = Log::$file)) return [];
    $size = filesize($file);
    $data = file_get_contents(Log::$file, false, null, $size - 2500, 2500);
    foreach (explode("\n", trim($data)) as $line) {
      $logs[] = ['key' => '-', 'val' => $line];
    }
    return $logs ?? [];
  }
}
