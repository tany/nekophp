<?php
namespace mongodb\actions;

class Collections extends \core\Action {

  use \core\actions\LoginFilter;
  use \mongodb\actions\ConnectionFilter;

  public static $partials = [
    'page-head' => '/mongodb/overviews/page-head',
    'page-navi' => null,
    'page-main' => '/mongodb/overviews/page-main',
    'page-foot' => null,
  ];

  public function index($request) {
    $this->collections = $this->conn->collections($request->db, ['size' => true]);
  }

  public function create($request) {
    $item = null;

    $this->item = $item;
  }

  public function show($request) {
    return $this->locate($request->getPath() . '/');
  }

  public function update($request) {
    $item = $request->coll;

    $this->item = $item;
  }

  public function createResource($request) {
    $name = $request->data['name'];
    $this->conn->command($request->db, ['create' => $name]);

    $item = ['name' > $name];
    return $this->response->sendJson($item)->locate("{$name}/", 201);
  }

  public function updateResource($request) {
    $fr = "{$request->db}.{$request->coll}";
    $to = "{$request->db}.{$request->data['name']}";
    $this->conn->adminCommand(['renameCollection' => $fr, 'to' => $to]);

    return $this->locate('.', 204);
  }

  public function deleteResource($request) {
    $this->conn->dropCollection($request->db, $request->coll);

    return $this->locate('.', 204);
  }

  public function deleteResources($request) {
    foreach ($request->id as $id) {
      $this->conn->dropCollection($request->db, $id);
    }
    return $this->locate('.', 204);
  }
}
