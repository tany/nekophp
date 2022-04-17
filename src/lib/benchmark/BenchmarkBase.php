<?php
namespace benchmark;

class BenchmarkBase {

  public $times = 1000000;

  public function __construct() {
    error_reporting(E_ALL);
    ob_end_flush();

    $this->__invoke();
  }

  public function h1($name, $times = null) {
    if ($times)  $this->times  = $times;

    print "\n{$name} (PHP " . PHP_VERSION . ")\n";
    print "--------------------\n";
  }

  public function h2($name, $times = null) {
    if ($times)  $this->times  = $times;

    print "\n# {$name}\n";
  }

  public function measure($name, $formula) {
    $start = microtime(true);
    for ($i = 0; $i < $this->times; $i++) $formula();
    $elapsed = number_format((microtime(true) - $start) * 1_000, 3);

    print sprintf("  - %-30s : %10s ms\n", $name, $elapsed);
  }
}
