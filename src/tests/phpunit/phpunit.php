<?php
namespace core;

$root = dirname(__DIR__, 3);
require $root . '/src/lib/core/Loader.php';

Loader::initialize($root);
Loader::include(LIB, APP);
Router::initialize();
ob_end_clean();

$pattern = isset($argv[1]) ? ROOT . "/{$argv[1]}" : APP . '/*/tests/**.php';
$errors = [];

Test::setupDriver();

foreach (glob($pattern) as $file) {
  if (!preg_match('/\/[A-Z]\w+Test\.php$/', $file)) continue;

  try {
    require $file;
  } catch (\Exception $e) {
    $message = preg_replace('/\(Session .*/', '', trim($e->getMessage()));
    if (!$message) $message = 'Uncaught ' . get_class($e);
    $trace = preg_replace('/(\(\d+\)).*/', '$1', $e->getTraceAsString());
    $trace = preg_replace("/\n/", "\n  ", $trace);
    $errors[] = "{$message}\n\n  $trace";
  }
}

Test::quitDriver();

if ($errors) {
  foreach ($errors as $key => $val) print "\n[{$key}] {$val}\n";
  exit(count($errors));
} else {
  $time = number_sigfig((microtime(true) - TIME_FLOAT) * 1000);
  print "\nTests are finished. ({$time} ms)\n";
}
