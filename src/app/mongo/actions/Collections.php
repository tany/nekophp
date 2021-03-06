<?php
namespace mongo\actions;

class Collections extends \core\Action {

  use \mongo\actions\BaseFilter;

  public static $overview = 'mongo.overview.table';

  public function index($request) {
    $this->items = $this->conn->collections($request->db, ['size' => true]);
  }

  public function create($request) {
    $item = null;

    $this->item = $item;
  }

  public function show($request) {
    $this->response->location($request->path() . '/');
  }

  public function update($request) {
    $item = $request->coll;

    $this->item = $item;
  }

  public function createResource($request) {
    $name = $request->data['name'];
    $this->conn->command($request->db, ['create' => $name]);

    $this->done(['status' => 201, 'location' => "{$name}/"]);
  }

  public function updateResource($request) {
    $fr = "{$request->db}.{$request->coll}";
    $to = "{$request->db}.{$request->data['name']}";
    $this->conn->adminCommand(['renameCollection' => $fr, 'to' => $to]);

    $this->done(['status' => 204, 'location' => '.']);
  }

  public function deleteResource($request) {
    $this->conn->dropCollection($request->db, $request->coll);

    $this->done(['status' => 204, 'location' => '.']);
  }

  public function deleteResources($request) {
    foreach ($request->id as $id) {
      $this->conn->dropCollection($request->db, $id);
    }
    $this->done(['status' => 204, 'location' => '.']);
  }
}
