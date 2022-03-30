<?php
namespace mongo\action;

trait BaseFilter {

  use \core\action\LoginFilter;
  use \mongo\action\ConnectionFilter;

  protected $loginRequired = false;
}
