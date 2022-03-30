<?php
namespace elastic\action;

trait BaseFilter {

  use \core\action\LoginFilter;
  use \elastic\action\ConnectionFilter;

  protected $loginRequired = false;
}
