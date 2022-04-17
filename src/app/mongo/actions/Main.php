<?php
namespace mongo\actions;

use \mongo\models\Connection;

class Main extends \core\Action {

  use \mongo\actions\BaseFilter;

  protected $connectionRequired = false;

  public function index($request) {
    $this->clients = Connection::listClients();
    if (!$request->isPost()) return;

    $name = $request->data['name'];

    $this->done(['location' => "/mongo/{$name}/"]);
  }
}
