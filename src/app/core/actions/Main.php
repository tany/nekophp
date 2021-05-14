<?php
namespace core\actions;

class Main extends \core\Action {

  use \core\actions\LoginFilter;

  protected $loginRequired = false;

  public function index($request) {
    ;
  }
}
