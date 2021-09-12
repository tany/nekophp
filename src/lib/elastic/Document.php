<?php
namespace elastic;

use \mongo\format\JSON;

class Document implements \JsonSerializable, \IteratorAggregate {

  use \core\feature\Accessor;
  use \elastic\feature\Queryable;

  public $_index;
  public $_type;
  public $_id;
  public $_meta;

  protected $_connection;
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
    if ($connection) $this->_connection = $connection;
    return $this;
  }

  /**
   * @param array $results [_index, _type, _id, _score, _source]
   * @param array $results [_index, _type, _id, _version, _seq_no, _primary_term, found,_source]
   */
  public function resultsUnserialize($results, $connection) {
    $this->_connection = $connection;
    $this->_index = $results['_index'];
    $this->_type = $results['_type'];
    $this->_id = $results['_id'];
    $this->_data = $results['_source'] ?? [];
    $this->_saved = $this->_data;
    unset($results['_source']);
    $this->_meta = $results;
    return $this;
  }

  public function isSaved() {
    return !empty($this->_saved);
  }

  public function jsonSerialize() {
    return array_map_recursive([JSON::class, 'encodeObject'], $this->_data);
  }

  public function jsonUnserialize($data) {
    $this->_data = JSON::decode($data) ?? [];
    foreach (['_index', '_type', '_id'] as $name) {
      if (isset($this->_data[$name])) $this->$name = $this->_data[$name];
      unset($this->_data[$name]);
    }
    return $this;
  }
}
