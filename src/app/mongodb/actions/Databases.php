<?php
namespace mongodb\actions;

class Databases extends \core\Action {

  use \core\actions\LoginFilter;
  use \mongodb\actions\ConnectionFilter;

  public static $partials = [
    'page-head' => '/mongodb/overviews/page-head',
    'page-navi' => null,
    'page-main' => '/mongodb/overviews/page-main',
    'page-foot' => null,
  ];

  public function index($request) {
    $this->databases = $this->conn->databases();
  }

  public function create($request) {
    $item = null;

    $this->item = $item;
  }

  public function createResource($request) {
    $name = $request->data['name'];
    $item = ['name' > $name];

    return $this->response->sendJson($item)->locate("{$name}/", 201);
  }

  public function deleteResources($request) {
    foreach ($request->id as $id) {
      $this->conn->dropDatabase($id);
    }
    return $this->locate('.', 204);
  }
}
