<?php
namespace elastic\action;

use \elastic\model\Connection;

class Main extends \core\Action {

  use \elastic\action\BaseFilter;

  protected $connectionRequired = false;

  public function index($request) {
    $this->clients = Connection::listClients();
    if (!$request->isPost()) return;

    $name = $request->data['name'];

    $this->done(['location' => "/elastic/{$name}/"]);
  }
}
