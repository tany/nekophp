<?php
namespace mongo\field;

class ObjectId {

  public static function isObjectId($id) {
    if (!is_string($id)) return false;
    return (($len = strlen($id)) === 12 || $len === 24) && preg_match("/^[0-9a-f]+$/", $id);
  }

  public static function castId($id) {
    if (self::isObjectId($id)) return new \MongoDB\BSON\ObjectId($id);
    if (is_int($id)) return $id;
    if (is_numeric($id)) return (int)$id;
    return $id;
  }

  // public function nextId() {
  //   if (isset($this->_id)) return;

  //   $command = [
  //     'findAndModify' => 'sequence',
  //     'query'         => ['_id' => "{$this->collectionName()}._id"],
  //     'update'        => ['$inc' => ['seq' => 1]],
  //     "upsert"        => true,
  //     "new"           => true,
  //   ];
  //   $cursor = $this->connection()->command($this->dbName(), $command);
  //   return current($cursor->toArray())->value->seq;
  // }
}
