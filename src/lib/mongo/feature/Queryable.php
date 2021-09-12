<?php
namespace mongo\feature;

use \core\utils\Pager;
use \mongo\Cursor;

trait Queryable {

  protected $_queryCriteria = [];
  protected $_queryOptions = [
    'sort' => ['$natural' => -1],
  ];

  public function where($query) {
    $this->_queryCriteria += $query;
    return $this;
  }

  public function sort($val) {
    $this->_queryOptions['sort'] = $val;
    return $this;
  }

  public function skip($val) {
    $this->_queryOptions['skip'] = $val;
    return $this;
  }

  public function limit($val) {
    $this->_queryOptions['limit'] = $val;
    return $this;
  }

  public function total() {
    $command = ['count' => $this->collection(), 'query' => $this->_queryCriteria ?: null];
    $cursor = $this->connection()->command($this->database(), $command);
    return current($cursor->toArray())->n;
  }

  public function find($id) {
    if (\mongo\field\ObjectId::isObjectId($id)) {
      $id = new \MongoDB\BSON\ObjectID($id);
    } elseif (is_numeric($id)) {
      $id = (int)$id;
    }
    $filter = ['_id' => $id] + $this->_queryCriteria;
    $cursor = $this->connection()->query($this->namespace(), $filter, $this->_queryOptions);
    $cursor->setTypeMap(['root' => static::class, 'document' => 'array']);
    return current($cursor->toArray()) ?: null;
  }

  public function findAll() {
    $cursor = $this->connection()->query($this->namespace(), $this->_queryCriteria, $this->_queryOptions);
    $cursor->setTypeMap(['root' => static::class, 'document' => 'array']);
    return $cursor->toArray();
  }

  public function findOne() {
    $options = ['limit' => 1] + $this->_queryOptions;
    $cursor = $this->connection()->query($this->namespace(), $this->_queryCriteria, $options);
    $cursor->setTypeMap(['root' => static::class, 'document' => 'array']);
    return current($cursor->toArray()) ?: null;
  }

  public function paginate($options = []) {
    $options['limit'] ??= $this->_queryOptions['limit'] ?? null;
    $options['total'] ??= $this->total();

    $pager = new Pager($options);
    $this->_queryOptions['skip']  = $pager->skip;
    $this->_queryOptions['limit'] = $pager->limit;

    $cursor = new Cursor($this->findAll());
    $cursor->pager = $pager;
    return $cursor;
  }

  public function save() {
    if ($this->isSaved()) return $this->update($this->_data);

    $this->_id = $this->insert($this->_data);
    return (bool)$this->_id;
  }

  public function insert($data) {
    return $this->connection()->insert($this->namespace(), $data);
  }

  public function update($update, $options = []) {
    $criteria ??= ['_id' => $this->_saved['_id']];
    return $this->connection()->update($this->namespace(), $criteria, $update, $options);
  }

  public function delete($options = []) {
    $criteria ??= ['_id' => $this->_saved['_id']];
    return $this->connection()->delete($this->namespace(), $criteria, $options);
  }

  public function updateAll($update, $options = []) {
    return $this->connection()->updateAll($this->namespace(), $this->_queryCriteria, $update, $options);
  }

  public function deleteAll($options = []) {
    return $this->connection()->deleteAll($this->namespace(), $this->_queryCriteria, $options);
  }
}
