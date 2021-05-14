<?php
namespace mongodb\actions;

use \mongodb\Connection;

class Main extends \core\Action {

  use \core\actions\LoginFilter;
  use \mongodb\actions\ConnectionFilter;

  protected $connectionRequired = false;

  protected function parseSettings($settings) {
    foreach ($settings as $key => $setting) {
      $connections[$key] = Connection::parseSetting($setting);
    }
    return $connections ?? [];
  }

  public function index($request) {
    $this->clients = $this->parseSettings(setting('mongodb.clients'));
    if (!$request->isPost()) return;

    $name = $request->data['name'];
    $conf = $this->clients[$name];

    if (!$this->connect($name, $conf)) {
      $this->alert($this->error->getMessage(), 'danger');
      return $this->locate();
    }

    $this->storeConnection($name);

    if ($db = $conf['database'] ?? null) {
      return $this->locate("/mongodb/db/{$db}/");
    } else {
      return $this->locate('/mongodb/db/');
    }
  }
}
