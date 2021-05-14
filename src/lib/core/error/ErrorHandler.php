<?php
namespace core\error;

use \core\error\ErrorReport;

class ErrorHandler {

  public static $error;

  /**
   * set_error_handler
   *   ->($errno, $errstr, $errfile, $errline)
   */
  public static function error($code, $message, $file, $line) {
    /**
     * ErrorException::__construct
     *   ($message, $code, $severity, $file, $line, $previous)
     */
    self::except(new \ErrorException($message, $code, E_ERROR, $file, $line));
  }

  /**
   * set_exception_handler
   *   ->(Exception $e)
   */
  public static function except($e) {
    log_error($e->getMessage() . "\n" . $e->getTraceAsString());

    if (defined('MODE') && MODE === 'production') exit;

    self::$error = $e;
    (new ErrorReport($e))->render();
    exit;
  }
}
