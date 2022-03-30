<?php
namespace ci;

use \HeadlessChromium\BrowserFactory;
use \HeadlessChromium\Page;
use \PHPUnit\Framework\TestCase;
use \ci\NestedNode;

class ActionTest extends TestCase {

  public static $browser;
  public static $page;
  public static $navigation;
  public static $baseUrl = 'http://neko.vm';
  public static $userAgent = 'HeadlessChrome LocalTest';

  public static function setupBrowser() {
    if (self::$browser) return;

    $factory = new BrowserFactory('google-chrome');
    self::$browser = $factory->createBrowser([
      'userAgent' => self::$userAgent,
    ]);
    self::$page = self::$browser->createPage();
    self::clearScreenshot();

    print "====================\n";
    print "# PHPUnit \n";
    print "- google-chrome\n";
    print "- " . self::$baseUrl . "\n";
    print "====================\n";
  }

  public static function closeBrowser() {
    if (self::$browser) self::$browser->close();
  }

  public static function clearScreenshot() {
    foreach (glob('tmp/ss-*.png') as $file) {
      unlink($file);
    }
  }

  public function screenshot($filename = null) {
    static $count;
    $count += 1;
    $filename ??= sprintf('ss-%02d.png', $count);
    self::$page->screenshot()->saveToFile("tmp/{$filename}");
  }

  public function visit($url) {
    $url = $url[0] === '/' ? self::$baseUrl . $url : $url;
    print "\n  " . str_shift($url, self::$baseUrl) . ' ... ';

    self::$navigation = self::$page->navigate($url);
    return self::$navigation->waitForNavigation();
  }

  public function get($code) {
    return self::$page->evaluate($code)->getReturnValue();
  }

  public function find($selector) {
    return self::$page->dom()->querySelector($selector);
  }

  public function name($name) {
    return $this->find('[name="' . $name . '"]');
  }

  public function click($selector) {
    print 'c';
    $this->find($selector)->click();
    return $this->within($selector);
  }

  public function link($selector) {
    print "l";
    $evaluation = self::$page->evaluate("(() => { document.querySelector('{$selector}').click(); })()");
    $evaluation->waitForPageReload(Page::LOAD, 2000); // ms
  }

  public function submit($selector) {
    print "s";
    $evaluation = self::$page->evaluate("(() => { document.querySelector('{$selector}').click(); })()");
    $evaluation->waitForPageReload(Page::LOAD, 5000); // ms
  }

  public function within($selector) {
    return new NestedNode($this, $selector);
  }

  // public function clickToDraw($selector) {
  //   $before = $this->get('document.all.length');
  //   $this->find($selector)->click();
  //   for ($i = 0; $i < 10; $i++) {
  //     usleep(200000);
  //     if (fn() => $before !== $this->get('document.all.length')) break;
  //   }
  // }
}
