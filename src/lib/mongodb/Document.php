<?php
namespace mongodb;

use \mongodb\format\JSON;
use \MongoDB\BSON\Unserializable;

class Document implements Unserializable, \JsonSerializable, \IteratorAggregate {

  use \core\feature\Accessor;
  use \mongodb\feature\Queryable;

  protected $connection;
  protected $namespace;
  protected $savedDocument;

  public function __construct($data = []) {
    $this->data = $data;
  }

  public function getIterator() {
    return new \ArrayIterator($this->queryCriteria ? $this->findAll() : $this->data);
  }

  public function connect() {
    if ($this->connection) return $this->connection;
    // return $this->_conn = ...
  }

  public function setConnection($connection) {
    if ($connection) $this->connection = $connection;
    return $this;
  }

  public function getNamespace() {
    if ($this->namespace) return $this->namespace;
    // return $this->namespace = ...
  }

  public function setNamespace($namespace) {
    if ($namespace) $this->namespace = $namespace;
    return $this;
  }

  public function getDatabase() {
    return str_pop($this->getNamespace(), '.');
  }

  public function getCollection() {
    return extname($this->getNamespace(), '');
  }

  public function isNewDocument() {
    return empty($this->savedDocument);
  }

  public function bsonUnserialize(array $data) {
    $this->connection = Connection::$lastConnection;
    $this->namespace  = Connection::$lastQuery['namespace'];
    $this->data = $data;
    $this->savedDocument = $data;
  }

  public function jsonSerialize() {
    return array_map_recursive([JSON::class, 'encodeObject'], $this->data);
  }

  public function jsonUnserialize($data) {
    $this->data = JSON::decode($data) ?? [];
    return $this;
  }
}
