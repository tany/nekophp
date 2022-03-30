<?php
namespace core\model;

class User {

  public $id = 1;
  public $name = 'user';

  protected $_errors = [];

  public static function find($id) {
    return new self;
  }

  public static function findByName($name) {
    if ($name) return new self;
    return null;
  }

  public function authenticate($username, $password) {
    $this->_errors[] = 'Please input username or password.';
    return false;
  }
}
