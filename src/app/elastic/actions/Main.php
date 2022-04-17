<?php
namespace elastic\actions;

use \elastic\models\Connection;

class Main extends \core\Action {

  use \elastic\actions\BaseFilter;

  protected $connectionRequired = false;

  public function index($request) {
    $this->clients = Connection::listClients();
    if (!$request->isPost()) return;

    $name = $request->data['name'];

    $this->done(['location' => "/elastic/{$name}/"]);
  }
}
