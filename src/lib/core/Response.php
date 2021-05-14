<?php
namespace core;

use \core\utils\MimeType;
use \core\storage\Cache;

class Response {

  public $header = [];
  public $code;
  public $file;
  public $data;

  public function header($val, $replace = true) {
    $this->header[] = [$val, $replace];
    return $this;
  }

  public function setCode($code) {
    $this->code = $code;
    return $this;
  }

  public function locate($url, $code = 302) {
    $this->setCode($code);
    $this->header("Location: {$url}");
    return $this;
  }

  public function setContentType($mime, $unknown = 'application/octet-stream') {
    if (!str_contains($mime, '/')) $mime = MimeType::fromName("f.{$mime}", $unknown);
    return $this->header("Content-Type: {$mime}");
  }

  public function setAttachment($filename) {
    return $this->header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($filename));
  }

  public function sendFile($file, $filename = null) {
    $this->file = $file;
    $filename ??= basename($file);
    $this->setContentType($filename, 'application/force-download');
    $this->setAttachment($filename);
    //$this->header('X-Accel-Redirect', $file);
    return $this;
  }

  public function sendData($data, $filename = null) {
    $this->data = $data;
    if ($filename) {
      $this->setContentType($filename, 'application/force-download');
      $this->setAttachment($filename);
    }
    return $this;
  }

  public function sendJson($data, $filename = null) {
    $this->data = json_encode_pretty($data);
    $this->header('Content-Type: application/json');
    if ($filename) $this->setAttachment($filename);
    return $this;
  }

  // public function cache() {
  //   $since = SERVER['HTTP_IF_MODIFIED_SINCE'] ?? 0;
  //   strtotime($since);

  //   if (Cache::$keep) {
  //     return $this->setCode(304);
  //   }
  //   $this->header[] = 'Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
  //   return;
  // }

  public function sendHeaders() {
    foreach ($this->header as [$val, $replace]) {
      header($val, $replace);
    }
    if ($this->code) {
      http_response_code($this->code);
    }
  }

  public function capture() {
    return ob_capture();
  }

  public function flash() {
    $this->sendHeaders();

    if ($this->file) {
      ob_end_clean();
      readfile($this->file);
    } elseif ($this->data) {
      ob_end_clean();
      print $this->data;
    }
    return $this;
  }
}
