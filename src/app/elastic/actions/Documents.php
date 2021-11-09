<?php
namespace elastic\actions;

use \elastic\AnyDocument;

class Documents extends \core\Action {

  use \core\actions\LoginFilter;
  use \elastic\actions\ConnectionFilter;

  public static $overview = 'elastic.overview.table';

  protected function model() {
    $model = new AnyDocument;
    $model->setConnection($this->conn);
    $model->_index = $this->request->index;
    return $model;
  }

  public function index($request) {
    $items = $this->model()
      ->keywordSearch($request->q)
      ->paginate();

    $this->items = $items;
    $this->fields = AnyDocument::collectFields($items);
  }

  public function create($request) {
    $item = $this->model();
    $item->setData(['name' => 'value']);

    $this->item = $item;
    $this->json = json_encode_pretty($item);
  }

  public function show($request) {
    $item = $this->model()->find($request->id) ?? abort(404);

    $this->item = $item;
    $this->fields = AnyDocument::sortFields($item);
  }

  public function update($request) {
    $item = $this->model()->find($request->id) ?? abort(404);
    $json = json_encode_pretty($item);

    $this->item = $item;
    $this->json = $json === '[]' ? '{}' : $json;
  }

  public function createResource($request) {
    $item = $this->model();
    $item->jsonUnserialize($request->data['json'])->save();

    $this->done(['status' => 201, 'location' => rawurlencode($item->_id)]);
  }

  public function updateResource($request) {
    $item = $this->model()->find($request->id) ?? abort(404);
    $item->jsonUnserialize($request->data['json'])->save();

    $this->done(['status' => 204, 'location' => rawurlencode($item->_id)]);
  }

  public function deleteResource($request) {
    $item = $this->model()->find($request->id) ?? abort(404);
    $item->delete();

    $this->done(['status' => 204, 'location' => '.']);
  }

  public function deleteResources($request) {
    $item = $this->model();
    $item->where(['terms' => ['_id' => [(object)$request->id]]])->deleteAll();

    $this->done(['status' => 204, 'location' => '.']);
  }
}
