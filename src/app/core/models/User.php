<?php
namespace core\models;

class User {

  public $id = 1;
  public $name = 'user';

  public static function find($id) {
    return new self;
  }

  public static function findByName($name) {
    if ($name) return new self;
    return null;
  }
}
