<?php
namespace core\features;

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

  public function setData($data) {
    $this->_data = $data + $this->_data;
    return $this;
  }

  public function exchangeData($data) {
    $this->_data = $data;
    return $this;
  }
}
