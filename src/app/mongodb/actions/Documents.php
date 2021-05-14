<?php
namespace mongodb\actions;

use \mongodb\AnyDocument;

class Documents extends \core\Action {

  use \core\actions\LoginFilter;
  use \mongodb\actions\ConnectionFilter;

  public static $partials = [
    'page-head' => '/mongodb/overviews/page-head',
    'page-navi' => null,
    'page-main' => '/mongodb/overviews/page-main',
    'page-foot' => null,
  ];

  protected function model() {
    $model = new AnyDocument;
    $model->setConnection($this->conn);
    $model->setNamespace("{$this->request->db}.{$this->request->coll}");
    return $model;
  }

  public function index($request) {
    $items = $this->model()
      ->textSearch($request->q)
      ->paginate();

    $this->items = $items;
    $this->fields = AnyDocument::collectFields($items) ?: ['No Data'];
  }

  public function create($request) {
    $item = $this->model();
    $item->setData(['Neko' => 'is not a dog.']);

    $this->item = $item;
  }

  public function show($request) {
    $item = $this->model()->find($request->id) ?? abort(404);

    $this->item = $item;
    $this->fields = $item->sortFields();
  }

  public function update($request) {
    $item = $this->model()->find($request->id) ?? abort(404);

    $this->item = $item;
  }

  public function createResource($request) {
    $item = $this->model();
    $item->jsonUnserialize($request->data['json'])->save();

    return $this->response->sendJson($item)->locate($item->_id, 201);
  }

  public function updateResource($request) {
    $item = $this->model()->find($request->id) ?? abort(404);
    $item->jsonUnserialize($request->data['json'])->save();

    return $this->locate($item->_id, 204);
  }

  public function deleteResource($request) {
    $item = $this->model()->find($request->id) ?? abort(404);
    $item->delete();

    return $this->locate('.', 204);
  }

  public function deleteResources($request) {
    $ids = array_map(fn($v) => \mongodb\type\ObjectId::castId($v), $request->id);
    $items = $this->model()->where(['_id' => ['$in' => $ids]]);

    foreach ($items as $item) {
      $item->delete();
    }
    return $this->locate('.', 204);
  }

  public function createTest($request) {
    $item = $this->model();
    $item->setData([
      'boolTrue'    => true,
      'boolFalse'   => false,
      'string'      => 'string',
      'integer'     => 1234,
      'float'       => 12.34,
      'array'       => [10, 20],
      'arrayHash'   => ['a' => 10, 'b' => 20],
      'Binary'      => new \MongoDB\BSON\Binary('binary', \MongoDB\BSON\Binary::TYPE_GENERIC),
      'Decimal128'  => new \MongoDB\BSON\Decimal128(1234.5678),
      'Javascript'  => new \MongoDB\BSON\Javascript('function() { return 1 }', ['foo' => 'bar']),
      'MinKey'      => new \MongoDB\BSON\MinKey,
      'MaxKey'      => new \MongoDB\BSON\MaxKey,
      'ObjectId'    => new \MongoDB\BSON\ObjectId('5a2493c33c95a1281836eb6a'),
      'Regex'       => new \MongoDB\BSON\Regex('^.*$', 'i'),
      'Timestamp'   => new \MongoDB\BSON\Timestamp(time(), 0),
      'UTCDateTime' => new \MongoDB\BSON\UTCDateTime(time() * 1000)
    ], true);
    $item->save();

    return $this->response->sendJson($item)->locate('.', 201);
  }
}
