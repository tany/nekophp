<?php

use \core\error\ErrorReport;
use \core\logger\Log;
use \ci\ActionTest;

require 'src/app/core/@conf/boot.php';

$paths = ($argc > 1) ? array_slice($argv, 1) : [APP . '/*/@ci'];

new class($paths) {

  public function __construct($paths) {
    Log::clear();
    ob_end_flush();

    $browser = ActionTest::setupBrowser();
    $page = ActionTest::setupPage($browser);
    $this->showTitle('Tests');

    foreach ($this->findFiles($paths) as $file) {
      if (!preg_match('/@ci\/.*Test\.php$/', $file)) continue;

      try {
        print "\n" . str_sub(ROOT . '/', '', $file);
        $test = require $file;
        $test->browser = $browser;
        $test->page = $page;
        $test->clearScreenshot();
        $test->beforeAll();
        $test->__invoke();
        $test->afterAll();
        print "\n";
      } catch (\Exception | \Error $e) {
        if (isset($test)) $test->screenshot('ss-exception.png');

        $message = preg_replace('/\(Session .*/', '', trim($e->getMessage()));
        if (!$message) $message = 'Uncaught ' . $e::class;
        print "\n\nError: {$message}\n";

        new ErrorReport($e);
        $errors[] = ob_get_clean();
      }
    }

    $browser->close();
    $this->showResults($errors ?? []);
    $this->showLog();

    exit(empty($errors) ? 0 : 1);
  }

  public function findFiles($paths) {
    $files = [];

    foreach ($paths as $path) {
      if (str_ends_with($path, '.php')) {
        $files[] = $path;
      } else {
        array_push($files, ...explode("\n", trim(`find {$path} -name "?*Test.php"`)));
      }
    }
    return $files ?? [];
  }

  protected function showTitle($title) {
    print "\n--------------------\n";
    print "# {$title}\n";
  }

  protected function showResults($errors) {
    if ($errors) return $this->showErrors($errors);

    $this->showTitle("Results\n");
    $time = number_sigfig((microtime(true) - TIME_FLOAT) * 1000);
    print "Tests are successful. ({$time} ms)\n\n";
  }

  protected function showErrors($errors) {
    $this->showTitle("CLI Error");
    foreach ($errors as $error) print "\n" . trim($error) . "\n";
    print "\n";
  }

  protected function showLog() {
    if (!$log = Log::read()) return;

    $this->showTitle("Application Log\n");
    print "{$log}\n";
  }
};

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
