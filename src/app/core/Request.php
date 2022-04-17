<?php
namespace core;

class Request {

  use \core\features\Accessor;

  protected $url;
  protected $method;
  protected $scheme;
  protected $host;
  protected $port;
  protected $path;
  protected $query;
  protected $params;

  public function __construct($build = true) {
    if (!$build) return;

    $this->url    = SERVER['HTTP_X_REWRITE_URL'] ?? SERVER['REQUEST_URI'] ?? '/';
    $this->method = strtoupper(SERVER['REQUEST_METHOD'] ?? 'GET');
    $this->scheme = SERVER['REQUEST_SCHEME'] ?? 'http';
    $this->host   = SERVER['HTTP_HOST'] ?? 'localhost';
    $this->port   = (int)(SERVER['SERVER_PORT'] ?? 80);
    $this->path   = str_pop($this->url, '?');
    $this->query  = SERVER['QUERY_STRING'] ?? null;
    $this->params = qs_parse($this->query);
    $this->_data  = $_REQUEST;
    return $this;
  }

  /**
   * @param array $data [url:, method:, schema:, host:, port:, path:, query:, data:]
   */
  public function set($data) {
    $parsed = isset($data['url']) ? parse_url($data['url']) : [];

    $this->method = strtoupper($data['method'] ?? $parsed['method'] ?? 'GET');
    $this->scheme = $data['scheme'] ?? $parsed['scheme'] ?? 'http';
    $this->host   = $data['host'] ?? $parsed['host'] ?? SERVER['HTTP_HOST'] ?? 'localhost';
    $this->port   = (int)($data['port'] ?? $parsed['port'] ?? 80);
    $this->path   = $data['path'] ?? $parsed['path'] ?? '/';
    $this->query  = $data['query'] ?? $parsed['query'] ?? null;
    $this->params = $data['params'] ?? qs_parse($this->query);
    $this->url    = $data['url'] ?? url_merge_query($this->path, $this->params);
    $this->_data  = array_merge($this->params, $data['data'] ?? []);
    return $this;
  }

  public function url() {
    return $this->url;
  }

  public function method() {
    return $this->method;
  }

  public function schema() {
    return $this->scheme;
  }

  public function host() {
    return $this->host;
  }

  public function port() {
    return $this->port;
  }

  public function path() {
    return $this->path;
  }

  public function query() {
    return $this->query;
  }

  public function params() {
    return $this->params;
  }

  public function isHead() {
    return $this->method === 'HEAD';
  }

  public function isGet() {
    return $this->method === 'GET';
  }

  public function isPost($strict = false) {
    if ($strict) return $this->method === 'POST' && !isset($this->_data['_method']);
    return $this->method === 'POST';
  }

  public function isPut() {
    if ($this->method === 'PUT') return true;
    return $this->method === 'POST' && ($this->_data['_method'] ?? '') === 'PUT';
  }

  public function isDelete() {
    if ($this->method === 'DELETE') return true;
    return $this->method === 'POST' && ($this->_data['_method'] ?? '') === 'DELETE';
  }

  public function isXhr() {
    return strtolower(SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
  }
}
