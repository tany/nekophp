<?php
namespace core\action;

class Main extends \core\Action {

  use \core\action\LoginFilter;

  protected $loginRequired = false;

  public function index($request) {
    ;
  }
}
