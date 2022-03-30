<?php
namespace core;

use \core\Core;
use \core\storage\Cache;
use \core\utils\MediaType;
use \core\view\Engine;

class Response {

  protected $headers = [];
  protected $status;
  protected $body;
  protected $file;
  protected $ready;

  public function __construct($scope) {
    $this->scope = $scope;
  }

  public function isReady() {
    return $this->ready || $this->headers;
  }

  public function header($val) {
    $this->headers[] = $val;
    return $this;
  }

  public function status($status) {
    $this->status = $status;
    return $this;
  }

  public function location($url, $status = null) {
    $url ??= Core::$request->path();
    $this->header("Location: {$url}");
    if ($status) $this->status($status);
    return $this;
  }

  public function type($type, $unknown = 'application/octet-stream') {
    if (!str_contains($type, '/')) $type = MediaType::search("f.{$type}", $unknown);
    return $this->header("Content-Type: {$type}");
  }

  public function filename($filename, $disposition = 'attachment') {
    return $this->header("Content-Disposition: {$disposition}; filename*=UTF-8''" . rawurlencode($filename));
  }

  public function file($file, $filename = null) {
    $filename ??= basename($file);
    $this->type($filename)->filename($filename);
    match (setting('--action.internalRedirection')) {
      'X-Accel-Redirect' => $this->header('X-Accel-Redirect: ' . str_replace(ROOT, '/x-accel', $file)),
      'X-SendFile' => $this->header("X-SendFile: {$file}"),
      default => ($this->file = $file),
    };
    return $this;
  }

  public function body($data, $filename = null) {
    $this->ready = true;
    $this->body = $data;
    if ($filename) $this->type($filename)->filename($filename);
    return $this;
  }

  public function json($data, $filename = null) {
    $this->ready = true;
    $this->body = json_encode_pretty($data);
    $this->type('application/json');
    if ($filename) $this->filename($filename);
    return $this;
  }

  // public function cache() {
  //   $since = SERVER['HTTP_IF_MODIFIED_SINCE'] ?? 0;
  //   strtotime($since);
  //   if (Cache::$keep) return $this->status(304);
  //   $this->header[] = 'Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
  //   return;
  // }

  public function render($file = null, $overview = true) {
    $this->ready = true;

    $scope = $this->scope;
    $path = str_snake(strtr($scope::class, '\\', '/'));
    $path = substr_replace($path, '/@views', strpos($path, '/'), 7); // strlen('/action')

    $view = new Engine($scope);
    $view->includePath($path)->render($file ?? $scope->action);
    if (!$overview) return $this;

    $view->setPartials($overviews = setting($scope::$overview));
    $view->renderOverview($overview === true ? $overviews['page-root'] : $overview);
    return $this;
  }

  public function capture() {
    $this->ready = null;
    return ob_capture();
  }

  public function flash() {
    foreach ($this->headers as $val) {
      header($val);
    }
    if ($this->status) {
      http_response_code($this->status);
    }

    if ($this->file) {
      ob_end_clean();
      readfile($this->file);
    } elseif ($this->body !== null) {
      ob_end_clean();
      print $this->body;
    }
  }
}
