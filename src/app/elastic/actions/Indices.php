<?php
namespace elastic\actions;

class Indices extends \core\Action {

  use \core\actions\LoginFilter;
  use \elastic\actions\ConnectionFilter;

  public static $overview = 'elastic.overview.table';

  public function index($request) {
    $this->items = $this->conn->catIndices();

    // $result = $this->conn->client->indices()->refresh(['index' => 's1']);
    // if (isset($result)) dump($result);
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

    $this->done(['status' => 201, 'location' => "{$name}/"]);
  }

  public function updateResource($request) {
    $this->conn->reindex($request->index, $request->data['name']);

    $this->done(['status' => 204, 'location' => '.']);
  }

  public function deleteResource($request) {
    $this->conn->deleteIndex($request->index);

    $this->done(['status' => 204, 'location' => '.']);
  }

  public function deleteResources($request) {
    $this->conn->deleteIndex(join(',', $request->id));

    $this->done(['status' => 204, 'location' => '.']);
  }

  public function open($request) {
    $results = $this->conn->client->indices()->open(['index' => $request->index]);
    $this->done(['results' => $results, 'location' => '.']);
  }

  public function close($request) {
    $results = $this->conn->client->indices()->close(['index' => $request->index]);
    $this->done(['results' => $results, 'location' => '.']);
  }

  public function mapping($request) {
    $results = $this->conn->client->indices()->getMapping(['index' => $request->index]);
    $this->done(['results' => $results]);
  }

  public function settings($request) {
    $results = $this->conn->client->indices()->getSettings(['index' => $request->index]);
    $this->done(['results' => $results]);
  }

  public function stats($request) {
    $results = $this->conn->client->indices()->stats();
    $this->done(['results' => $results]);
  }

  public function segments($request) {
    $results = $this->conn->client->indices()->segments(['index' => $request->index]);
    $this->done(['results' => $results]);
  }

  public function recovery($request) {
    $results = $this->conn->client->indices()->recovery(['index' => $request->index]);
    $this->done(['results' => $results]);
  }

  public function clearCache($request) {
    $results = $this->conn->client->indices()->clearCache(['index' => $request->index]);
    $this->done(['results' => $results]);
  }

  public function refresh($request) {
    $results = $this->conn->client->indices()->refresh(['index' => $request->index]);
    $this->done(['results' => $results]);
  }

  public function flush($request) {
    $results = $this->conn->client->indices()->flush(['index' => $request->index]);
    $this->done(['results' => $results]);
  }

  public function flushSynced($request) {
    $results = $this->conn->client->indices()->flushSynced(['index' => $request->index]);
    $this->done(['results' => $results]);
  }

  public function getAlias($request) {
    $results = $this->conn->client->indices()->getAlias(['index' => $request->index]);
    $this->done(['results' => $results]);
  }

  public function getUpgrade($request) {
    $results = $this->conn->client->indices()->getUpgrade(['index' => $request->index]);
    $this->done(['results' => $results]);
  }
}
