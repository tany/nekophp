<?php
namespace mongo\model;

use \mongo\format\JSON;
use \MongoDB\BSON\Unserializable;

class Document implements Unserializable, \JsonSerializable, \IteratorAggregate {

  use \core\feature\Accessor;
  use \mongo\feature\Queryable;

  protected $_connection;
  protected $_namespace;
  protected $_saved;

  public function __construct($data = []) {
    $this->_data = $data;
  }

  public function getIterator() {
    return new \ArrayIterator($this->_data);
  }

  public function connection() {
    if ($this->_connection) return $this->_connection;
    // return $this->_connection = ...
  }

  public function setConnection($connection) {
    $this->_connection = $connection;
    return $this;
  }

  public function namespace() {
    if ($this->_namespace) return $this->_namespace;
    // return $this->_namespace = ...
  }

  public function setNamespace($namespace) {
    $this->_namespace = $namespace;
    return $this;
  }

  public function database() {
    return str_pop($this->namespace(), '.');
  }

  public function collection() {
    return extname($this->namespace(), '');
  }

  public function isSaved() {
    return !empty($this->_saved);
  }

  public function bsonUnserialize(array $data) {
    $this->_connection = Connection::$lastConnection;
    $this->_namespace  = Connection::$lastQuery['namespace'];
    $this->_data = $data;
    $this->_saved = $data;
    return $this;
  }

  public function jsonSerialize() {
    return array_map_recursive([JSON::class, 'encodeObject'], $this->_data);
  }

  public function jsonUnserialize($data) {
    $this->_data = JSON::decode($data) ?? [];
    return $this;
  }
}
