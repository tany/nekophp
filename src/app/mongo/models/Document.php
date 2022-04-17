<?php
namespace mongo\models;

use \MongoDB\BSON\Unserializable;
use \mongo\utils\format\JSON;

class Document implements Unserializable, \JsonSerializable, \IteratorAggregate {

  use \core\features\Accessor;
  use \mongo\models\Queryable;

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
    $this->_saved = $this->_data;
    return $this;
  }

  public function jsonSerialize() {
    return JSON::encodeObjects($this->_data);
  }

  public function jsonUnserialize($data) {
    $this->_data = JSON::decode($data) ?? [];
    return $this;
  }
}
