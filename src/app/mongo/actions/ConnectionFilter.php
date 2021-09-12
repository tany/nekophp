<?php
namespace mongo\actions;

use \core\storage\Session;
use \mongo\Connection;

trait ConnectionFilter {

  protected $loginRequired = false;

  protected function connectBefore($request) {
    $required = $this->connectionRequired ?? true;
    if (!$required) return;

    $name = Session::get('mongo.client');
    if (!$name) return $this->disconnect();

    $this->conn = $this->connect($name, setting('mongo.clients')[$name]);
    if (!$this->conn) return $this->disconnect();
  }

  protected function connect($name, $conf) {
    try {
      $conn = Connection::connect($name, $conf);
      $conn->adminCommand(['serverStatus' => 1]);
    } catch (\Throwable $e) {
      $this->error = $e->getMessage();
      return false;
    }
    return $conn;
  }

  protected function storeConnection($name) {
    Session::set('mongo.client', $name);
  }

  protected function disconnect() {
    Session::delete('mongo.client');
    $this->response->location('/mongo');
  }
}
