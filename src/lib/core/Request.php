<?php
namespace core;

//use \core\feature\AccessorClass;

class Request {

  use \core\feature\Accessor;

  protected $url;
  protected $method;
  protected $scheme;
  protected $host;
  protected $port;
  protected $path;
  protected $query;

  public function __construct($build = true) {
    if (!$build) return;

    $this->url    = SERVER['HTTP_X_REWRITE_URL'] ?? SERVER['REQUEST_URI'] ?? '/';
    $this->method = strtoupper(SERVER['REQUEST_METHOD'] ?? 'GET');
    $this->scheme = SERVER['REQUEST_SCHEME'] ?? 'http';
    $this->host   = SERVER['HTTP_HOST'] ?? 'localhost';
    $this->port   = (int)(SERVER['SERVER_PORT'] ?? 80);
    $this->path   = rawurldecode(str_pop($this->url, '?'));
    $this->query  = qs_parse(SERVER['QUERY_STRING'] ?? null);
    $this->data   = $_REQUEST;
    return $this;
  }

  /**
   * @param array $data [url:, method:, schema:, host:, port:, path:, query:, data:]
   */
  public function set($data) {
    $parsed = isset($data['url']) ? parse_url($data['url']) : [];

    $this->method = strtoupper($data['method'] ?? $parsed['method'] ?? 'GET');
    $this->scheme = $data['scheme'] ?? $parsed['scheme'] ?? 'http';
    $this->host   = $data['host'] ?? $parsed['host'] ?? 'localhost';
    $this->port   = (int)($data['port'] ?? $parsed['port'] ?? 80);
    $this->path   = rawurldecode($data['path'] ?? $parsed['path'] ?? '/');
    $this->query  = $data['query'] ?? qs_parse($parsed['query'] ?? null);
    $this->data   = array_merge($this->query, $data['data'] ?? []);
    $this->url    = $data['url'] ?? url_merge_query($this->path, $this->query);
    return $this;
  }

  public function getUrl() {
    return $this->url;
  }

  public function getMethod() {
    return $this->method;
  }

  public function getSchema() {
    return $this->scheme;
  }

  public function getHost() {
    return $this->host;
  }

  public function getPort() {
    return $this->port;
  }

  public function getPath() {
    return $this->path;
  }

  public function getQuery() {
    return $this->query;
  }

  public function isHead() {
    return $this->method === 'HEAD';
  }

  public function isGet() {
    return $this->method === 'GET';
  }

  public function isPost($strict = false) {
    if ($strict) return $this->method === 'POST' && !isset($this->data['_method']);
    return $this->method === 'POST';
  }

  public function isPut() {
    if ($this->method === 'PUT') return true;
    return $this->method === 'POST' && ($this->data['_method'] ?? '') === 'PUT';
  }

  public function isDelete() {
    if ($this->method === 'DELETE') return true;
    return $this->method === 'POST' && ($this->data['_method'] ?? '') === 'DELETE';
  }

  public function isXhr() {
    return strtolower(SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
  }
}
