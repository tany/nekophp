<?php
namespace elastic\actions;

class Indices extends \core\Action {

  use \core\actions\LoginFilter;
  use \elastic\actions\ConnectionFilter;

  public static $overview = 'elastic.overview.table';

  public function index($request) {
    $this->items = $this->conn->indices();
  }

  public function create($request) {
    $item = null;

    $this->item = $item;
  }

  public function update($request) {
    $item = $request->index;

    $this->item = $item;
  }

  public function createResource($request) {
    $name = $request->data['name'];
    $this->conn->createIndex($name);

    $this->done("{$name}/", 201);
  }

  public function updateResource($request) {
    $this->conn->reindex($request->index, $request->data['name']);

    $this->done('.', 204);
  }

  public function deleteResource($request) {
    $this->conn->deleteIndex($request->index);

    $this->done('.', 204);
  }

  public function deleteResources($request) {
    $this->conn->deleteIndex(join(',', $request->id));

    $this->done('.', 204);
  }
}
