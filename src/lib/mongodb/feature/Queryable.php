<?php
namespace mongodb\feature;

use \core\utils\Pager;
use \mongodb\Cursor;

trait Queryable {

  protected $queryCriteria = [];
  protected $queryOptions = [
    'sort' => ['$natural' => -1],
  ];

  public function where($query) {
    $this->queryCriteria += $query;
    return $this;
  }

  public function sort($val) {
    $this->queryOptions['sort'] = $val;
    return $this;
  }

  public function skip($val) {
    $this->queryOptions['skip'] = $val;
    return $this;
  }

  public function limit($val) {
    $this->queryOptions['limit'] = $val;
    return $this;
  }

  public function findAll() {
    $cursor = $this->connect()->query($this->getNamespace(), $this->queryCriteria, $this->queryOptions);
    $cursor->setTypeMap(['root' => static::class, 'document' => 'array']);
    return $cursor->toArray();
  }

  public function findOne() {
    $options = ['limit' => 1] + $this->queryOptions;
    $cursor = $this->connect()->query($this->getNamespace(), $this->queryCriteria, $options);
    $cursor->setTypeMap(['root' => static::class, 'document' => 'array']);
    return current($cursor->toArray()) ?: null;
  }

  public function find($id) {
    if (\mongodb\type\ObjectId::isObjectId($id)) {
      $id = new \MongoDB\BSON\ObjectID($id);
    } elseif (is_numeric($id)) {
      $id = (int)$id;
    }
    $filter = ['_id' => $id] + $this->queryCriteria;
    $cursor = $this->connect()->query($this->getNamespace(), $filter, $this->queryOptions);
    $cursor->setTypeMap(['root' => static::class, 'document' => 'array']);
    return current($cursor->toArray()) ?: null;
  }

  public function size() {
    $command = ['count' => $this->getCollection(), 'query' => $this->queryCriteria ?: null];
    $cursor = $this->connect()->command($this->getDatabase(), $command);
    return current($cursor->toArray())->n;
  }

  public function paginate($options = []) {
    $options['limit'] ??= $this->queryOptions['limit'] ?? 50;
    $pager = new Pager($this->size(), $options);

    $this->queryOptions['skip']  = $pager->skip;
    $this->queryOptions['limit'] = $pager->limit;

    $cursor = new Cursor($this->findAll());
    $cursor->pager = $pager;
    return $cursor;
  }

  public function insert($data) {
    return $this->connect()->insert($this->getNamespace(), $data);
  }

  public function update($data, $criteria = null) {
    $criteria ??= ['_id' => $this->savedDocument['_id']];
    return $this->connect()->update($this->getNamespace(), $criteria, $data);
  }

  public function delete($criteria = null) {
    $criteria ??= ['_id' => $this->savedDocument['_id']];
    return $this->connect()->delete($this->getNamespace(), $criteria);
  }

  public function updateAll($data, $criteria, $options = []) {
    return $this->connect()->updateAll($this->getNamespace(), $criteria, $data, $options);
  }

  public function deleteAll($criteria, $options = []) {
    return $this->connect()->deleteAll($this->getNamespace(), $criteria, $options);
  }

  public function save() {
    if (!$this->isNewDocument()) return $this->update($this->data);

    $this->_id = $this->insert($this->data);
    return (bool)$this->_id;
  }
}
