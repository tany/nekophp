<?php
namespace core;

use \Facebook\WebDriver\WebDriverBy;
use \Facebook\WebDriver\WebDriverExpectedCondition;
use \Facebook\WebDriver\Chrome\ChromeDriver;
use \Facebook\WebDriver\Chrome\ChromeOptions;
use \Facebook\WebDriver\Exception\NoSuchElementException;
use \Facebook\WebDriver\Remote\DesiredCapabilities;
use \Facebook\WebDriver\Remote\RemoteWebDriver;
use \PHPUnit\Framework\TestCase;

class Test extends TestCase {

  public static $driver;
  public static $baseUrl = 'http://neko.vm';
  public static $userAgent = 'HeadlessChrome LocalTest';

  public function __construct() {
    $file = (new \ReflectionClass($this))->getFileName();
    $file = preg_replace('|^' . preg_quote(getcwd()) . '/|', '', $file);
    print "{$file}\n";

    try {
      $this->__invoke();
    } catch (\Exception $e) {
      $this->screenshot('ss-exception.png');
      throw $e;
    }
    $this->after();
  }

  public function after() {
    // finally processes
  }

  /**
   * @see https://peter.sh/experiments/chromium-command-line-switches/
   */
  public static function setupDriver() {
    if (self::$driver) return;

    $chromeOptions = new ChromeOptions();
    $chromeOptions->addArguments([
      '--disable-gpu',
      '--headless',
      '--no-sandbox',
      '--user-agent=' . self::$userAgent,
    ]);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);
    $driver = ChromeDriver::start($capabilities);
    // $devTools = $driver->getDevTools();

    self::$driver = $driver;

    print "------------------------------\n";
    print ' Driver: ChromeDriver' . "\n";
    print ' URL: ' . self::$baseUrl . "\n";
    print "------------------------------\n";
  }

  public static function quitDriver() {
    self::$driver->quit();
    self::$driver = null;
  }

  public function visit($path) {
    $url = $path[0] === '/' ? self::$baseUrl . $path : $path;
    print ' -> ' . str_shift($url, self::$baseUrl) . "\n";

    self::$driver->get($url);
    self::$driver->wait(2, 300)->until(function ($driver) use ($url) {
      return $driver->getCurrentURL() === $url;
    });
    return self::$driver;
  }

  /**
   *  Returns the RemoteWebElement
   */
  public function find($selector) {
    try {
      return self::$driver->findElement(WebDriverBy::cssSelector($selector));
    } catch (NoSuchElementException $e) {
      //print self::$driver->getPageSource() . "\n";
      throw $e;
    }
  }

  public function name($selector) {
    return self::$driver->findElement(WebDriverBy::name($selector));
  }

  /**
   *  Returns the array of RemoteWebElement
   */
  public function findAll($selector) {
    try {
      return self::$driver->findElements(WebDriverBy::cssSelector($selector));
    } catch (NoSuchElementException $e) {
      // print self::$driver->getPageSource() . "\n";
      throw $e;
    }
  }

  /**
   * Click and Wait
   */
  public function click($selector, $wait = true) {
    $lastUrl = self::$driver->getCurrentURL();
    $lastCnt = self::$driver->executeScript('return document.all.length');
    //$lastCnt = self::$driver->executeScript('return $(":visible").length');
    //$lastCnt = count(self::$driver->findElements(WebDriverBy::cssSelector('*')));

    $this->find($selector)->click();

    if ($wait === true) {
      self::$driver->wait(2, 300)->until(function ($driver) use ($lastCnt) {
        return $lastCnt !== $driver->executeScript('return document.all.length');
      });
      if (($url = self::$driver->getCurrentURL()) !== $lastUrl) {
        print ' -> ' . str_shift($url, self::$baseUrl) . "\n";
      }
    }
  }

  /**
   * Puts the ScreenShot
   */
  public function screenshot($filename = null) {
    static $count;
    $count += 1;

    $filename ??= sprintf('ss-%02d.png', $count);
    self::$driver->takeScreenshot("tmp/{$filename}");
  }
}
