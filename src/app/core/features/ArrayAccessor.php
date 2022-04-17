<?php
namespace core\features;

/**
 * Need to implements \ArrayAccess
 */
trait ArrayAccessor {

  protected $_data = [];

  public function offsetExists($key) {
    return isset($this->_data[$key]);
  }

  public function offsetGet($key) {
    return $this->_data[$key] ?? null;
  }

  public function offsetSet($key, $val) {
    if ($key === null) {
      $this->_data[] = $val;
    } else {
      $this->_data[$key] = $val;
    }
  }

  public function offsetUnset($key) {
    unset($this->_data[$key]);
  }
}
