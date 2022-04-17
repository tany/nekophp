<?php
namespace elastic\actions;

use \elastic\models\Connection;

trait ConnectionFilter {

  // protected $connectionRequired = true;

  protected function connectBefore($request) {
    $required = $this->connectionRequired ?? true;
    if (!$required) return;

    $name = $request->client;
    $conf = setting('elastic.clients')[$name] ?? null;

    if (!$conf) return $this->disconnect();
    $this->conn = $this->connect($name, $conf);
    if (!$this->conn) return $this->disconnect();
  }

  protected function connect($name, $conf) {
    try {
      $conn = Connection::connect($name, $conf);
      $conn->client->ping();
    } catch (\Throwable $e) {
      $this->alert('Error: ' . $e->getMessage(), 'danger');
      return false;
    }
    return $conn;
  }

  protected function disconnect() {
    $this->response->location('/elastic');
  }
}
