<?php
namespace core\utils\string;

class Regexp {

  public $pattern;
  public $delimiter;
  public $source;

  public function __construct($pattern, $delimiter = '/') {
    $this->pattern = $pattern;
    $this->delimiter = $delimiter;
    $this->source = str_shift(str_rpop($pattern, $delimiter), $delimiter);
  }

  public function match($value) {
    return preg_match($this->pattern, $value);
  }
}
