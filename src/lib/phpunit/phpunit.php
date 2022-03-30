<?php

use \core\Loader;
use \core\Router;
use \ci\ActionTest;

$root = dirname(__DIR__, 3);
require $root . '/src/app/core/Loader.php';

Loader::initialize($root);
Loader::include(APP, LIB);
Router::initialize();
ob_end_flush();

$paths = ($argc > 1) ? array_slice($argv, 1) : [APP . '/*/@ci'];
$files = [];

foreach ($paths as $path) {
  if (str_ends_with($path, '.php')) {
    $files[] = $path;
  } else {
    array_push($files, ...explode("\n", trim(`find {$path} -name "?*Test.php"`)));
  }
}

ActionTest::setupBrowser();

try {
  foreach ($files as $file) {
    if (!preg_match('/@ci\/.*Test\.php$/', $file)) continue;

    print "\n{$file}";
    $test = require $file;
    $test->__invoke();
  }
} catch (\Exception $e) {
  $test->screenshot('ss-exception.png');
  $message = preg_replace('/\(Session .*/', '', trim($e->getMessage()));
  if (!$message) $message = 'Uncaught ' . get_class($e);
  $trace = preg_replace('/(\(\d+\)).*/', '$1', $e->getTraceAsString());
  $trace = preg_replace("/\n/", "\n  ", $trace);
  $errors[] = "{$message}\n\n  $trace";
}

ActionTest::closeBrowser();

if (empty($errors)) {
  $time = number_sigfig((microtime(true) - TIME_FLOAT) * 1000);
  print "\n\nTests are finished. ({$time} ms)\n";
} else {
  foreach ($errors as $key => $val) print "\n[{$key}] {$val}\n";
  exit(count($errors));
}

/*
  HeadlessChromium\Dom\Node methods
  0 : __construct <str>
  1 : getAttributes <str>
  2 : setAttributeValue <str>
  3 : querySelector <str>
  4 : querySelectorAll <str>
  5 : focus <str>
  6 : getAttribute <str>
  7 : getPosition <str>
  8 : hasPosition <str>
  9 : getHTML <str>
  10 : getText <str>
  11 : scrollIntoView <str>
  12 : click <str>
  13 : sendKeys <str>
  14 : sendFile <str>
  15 : sendFiles <str>
  16 : assertNotError <str>

  HeadlessChromium\Page
  0 : __construct <str>
  1 : addPreScript <str>
  2 : getLayoutMetrics <str>
  3 : getFrameManager <str>
  4 : getSession <str>
  5 : setBasicAuthHeader <str>
  6 : setDownloadPath <str>
  7 : setExtraHTTPHeaders <str>
  8 : navigate <str>
  9 : evaluate <str>
  10 : callFunction <str>
  11 : addScriptTag <str>
  12 : getCurrentLifecycle <str>
  13 : hasLifecycleEvent <str>
  14 : waitForReload <str>
  15 : getFullPageClip <str>
  16 : screenshot <str>
  17 : pdf <str>
  18 : setDeviceMetricsOverride <str>
  19 : setViewport <str>
  20 : mouse <str>
  21 : keyboard <str>
  22 : dom <str>
  23 : close <str>
  24 : assertNotClosed <str>
  25 : setTimezone <str>
  26 : getCurrentUrl <str>
  27 : getHtml <str>
  28 : readCookies <str>
  29 : readAllCookies <str>
  30 : getCookies <str>
  31 : getAllCookies <str>
  32 : setCookies <str>
  33 : setUserAgent <str>
*/
