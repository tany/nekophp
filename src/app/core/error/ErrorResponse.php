<?php
namespace core\error;

class ErrorResponse extends \Exception {

  public $code;

  protected $message = 'HTTP Error Response';

  public function __construct($code) {
    $this->code = $code;
  }
}
