<?php
namespace elastic\actions;

use \core\storage\Session;
use \elastic\Connection;

trait ConnectionFilter {

  protected $loginRequired = false;

  protected function connectBefore($request) {
    $required = $this->connectionRequired ?? true;
    if (!$required) return;

    $name = Session::get('elastic.client');
    if (!$name) return $this->disconnect();

    $this->conn = $this->connect($name, setting('elastic.clients')[$name]);
    if (!$this->conn) return $this->disconnect();
  }

  protected function connect($name, $conf) {
    try {
      $conn = Connection::connect($name, $conf);
      $conn->client->ping();
    } catch (\Throwable $e) {
      $this->error = $e->getMessage();
      return false;
    }
    return $conn;
  }

  protected function storeConnection($name) {
    Session::set('elastic.client', $name);
  }

  protected function disconnect() {
    Session::delete('elastic.client');
    $this->response->location('/elastic');
  }
}
