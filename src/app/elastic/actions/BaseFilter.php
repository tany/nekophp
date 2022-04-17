<?php
namespace elastic\actions;

trait BaseFilter {

  use \core\actions\LoginFilter;
  use \elastic\actions\ConnectionFilter;

  protected $loginRequired = false;
}
