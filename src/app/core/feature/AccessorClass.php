<?php
namespace core\feature;

class AccessorClass {

  use Accessor;
  use ArrayAccess;

  public function __construct($data = []) {
    $this->_data = $data;
  }
}
