<?php
namespace elastic\actions;

use \elastic\models\AnyDocument;

class Documents extends \core\Action {

  use \elastic\actions\BaseFilter;

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
    $this->fields = $this->model()->cellFields($items);
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
    $this->fields = $item->showFields();
  }

  public function update($request) {
    $item = $this->model()->find($request->id) ?? abort(404);

    $this->item = $item->sortValues();
    $this->json = json_encode_pretty($item);
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

  public function addTest($request) {
    $item = $this->model()->setData([
      'name'        => date('U'),
      'null'        => null,
      'boolTrue'    => true,
      'boolFalse'   => false,
      'string'      => 'string',
      'strBlank'    => '',
      'strDate'     => date('c'),
      'numZero'     => 0,
      'numInt'      => 1234,
      'numFloat'    => 1234.00,
      'array'       => [10, 20],
      'arrayBlank'  => [],
      'hash'        => ['a' => 10, 'b' => 20],
      'hashObject'  => (object)['c' => 30, 'd' => 40],
      'hashBlank'   => (object)[],
    ], true);
    $item->save();

    $this->done(['status' => 201, 'location' => '.']);
  }
}
