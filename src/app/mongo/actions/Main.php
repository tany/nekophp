<?php
namespace mongo\actions;

use \mongo\Connection;

class Main extends \core\Action {

  use \core\actions\LoginFilter;
  use \mongo\actions\ConnectionFilter;

  protected $connectionRequired = false;

  public function index($request) {
    $this->clients = Connection::listClients();
    if (!$request->isPost()) return;

    $name = $request->data['name'];
    $conf = $this->clients[$name];

    if (!$this->connect($name, $conf)) {
      return $this->fail(500, 'Connection Error', $this->error);
    }

    $this->storeConnection($name);
    $this->done('/mongo/db/', 200);
  }
}
