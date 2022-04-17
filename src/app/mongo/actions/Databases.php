<?php
namespace mongo\actions;

class Databases extends \core\Action {

  use \mongo\actions\BaseFilter;

  public static $overview = 'mongo.overview.table';

  public function index($request) {
    $this->databases = $this->conn->databases();
  }

  public function create($request) {
    $item = null;

    $this->item = $item;
  }

  public function createResource($request) {
    $name = $request->data['name'];

    $this->done(['status' => 201, 'location' => "{$name}/"]);
  }

  public function deleteResource($request) {
    $this->conn->dropDatabase($request->db);

    $this->done(['status' => 204, 'location' => '.']);
  }

  public function deleteResources($request) {
    foreach ($request->id as $id) {
      $this->conn->dropDatabase($id);
    }
    $this->done(['status' => 204, 'location' => '.']);
  }
}
