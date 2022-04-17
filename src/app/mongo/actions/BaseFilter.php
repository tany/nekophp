<?php
namespace mongo\actions;

trait BaseFilter {

  use \core\actions\LoginFilter;
  use \mongo\actions\ConnectionFilter;

  protected $loginRequired = false;
}
