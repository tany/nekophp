<?php
namespace core\feature;

trait Accessor {

  protected $data = [];

  public function __isset($key) {
    return isset($this->data[$key]);
  }

  public function __get($key) {
    return $this->data[$key] ?? null;
  }

  public function __set($key, $val) {
    $this->data[$key] = $val;
  }

  public function __unset($key) {
    unset($this->data[$key]);
  }

  public function getData() {
    return $this->data;
  }

  public function setData($data, $reset = false) {
    $this->data = $reset ? $data : $data + $this->data;
    return $this;
  }
}
