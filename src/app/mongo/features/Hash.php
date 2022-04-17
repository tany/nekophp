<?php
namespace mongo\features;

use \MongoDB\BSON\Unserializable;

class Hash extends \stdClass implements Unserializable {

  public function bsonUnserialize($data) {
    foreach ($data as $key => $val) {
      $this->$key = $val;
    }
  }
}
