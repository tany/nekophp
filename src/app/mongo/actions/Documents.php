<?php
namespace mongo\actions;

use \mongo\models\AnyDocument;

class Documents extends \core\Action {

  use \mongo\actions\BaseFilter;

  public static $overview = 'mongo.overview.table';

  protected function model() {
    $model = new AnyDocument;
    $model->setConnection($this->conn);
    $model->setNamespace("{$this->request->db}.{$this->request->coll}");
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
    $this->fields = $item->showFields($item);
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
    $ids = array_map(fn($v) => \mongo\field\ObjectId::castId($v), $request->id);
    $this->model()
      ->where(['_id' => ['$in' => $ids]])
      ->deleteAll();

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

    $this->done(['status' => 201, 'location' => '.']);
  }
}
