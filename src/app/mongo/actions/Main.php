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

    $this->done(['location' => "/mongo/{$name}/"]);
  }
}
