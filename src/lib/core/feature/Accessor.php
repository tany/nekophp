<?php
namespace core\feature;

trait Accessor {

  protected $_data = [];

  public function __isset($key) {
    return isset($this->_data[$key]);
  }

  public function __get($key) {
    return $this->_data[$key] ?? null;
  }

  public function __set($key, $val) {
    $this->_data[$key] = $val;
  }

  public function __unset($key) {
    unset($this->_data[$key]);
  }

  public function data() {
    return $this->_data;
  }

  public function setData($data, $reset = false) {
    $this->_data = $reset ? $data : $data + $this->_data;
    return $this;
  }
}
