<?php
namespace core\feature;

trait ArrayAccess {

  protected $data = [];

  public function offsetExists($key) {
    return isset($this->data[$key]);
  }

  public function offsetGet($key) {
    return $this->data[$key] ?? null;
  }

  public function offsetSet($key, $val) {
    if ($key === null) {
      $this->data[] = $val;
    } else {
      $this->data[$key] = $val;
    }
  }

  public function offsetUnset($key) {
    unset($this->data[$key]);
  }
}
