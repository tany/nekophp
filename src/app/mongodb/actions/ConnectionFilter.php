<?php
namespace mongodb\actions;

use \core\storage\Session;
use \mongodb\Connection;

trait ConnectionFilter {

  // protected $connectionRequired = true;

  protected function connectBefore($request) {
    $required = $this->connectionRequired ?? true;
    if (!$required) return;

    $name = Session::get('mongodb.client');
    if (!$name) return $this->disconnect();

    $conf = setting('mongodb.clients')[$name];
    $this->conn = $this->connect($name, $conf);

    if (!$this->conn) return $this->disconnect();
  }

  protected function connect($name, $conf) {
    try {
      $conn = Connection::connect($name, $conf);
      $conn->adminCommand(['serverStatus' => 1]);
    } catch (\Throwable $e) {
      $this->error = $e;
      return false;
    }
    return $conn;
  }

  protected function storeConnection($name) {
    Session::set('mongodb.client', $name);
  }

  protected function disconnect() {
    Session::delete('mongodb.client');
    return $this->locate('/mongodb');
  }
}
