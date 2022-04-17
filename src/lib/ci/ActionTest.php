<?php
namespace ci;

use \HeadlessChromium\BrowserFactory;
use \HeadlessChromium\Page;
use \PHPUnit\Framework\TestCase;
use \ci\NestedNode;

class ActionTest extends TestCase {

  public static $userAgent = 'HeadlessChrome';
  public static $baseUrl = 'http://neko.vm';
  public $browser;
  public $page;

  public static function setupBrowser() {
    $factory = new BrowserFactory('google-chrome');

    return $factory->createBrowser([
      'userAgent' => self::$userAgent,
    ]);
  }

  public static function setupPage($browser) {
    print "--------------------\n";
    print "# PHPUnit \n\n";
    print "  URL: " . self::$baseUrl . "\n";
    print "  UserAgent: " . self::$userAgent . "\n";

    return $browser->createPage();
  }

  public function clearScreenshot() {
    foreach (glob('tmp/ss-*.png') as $file) {
      unlink($file);
    }
  }

  public function screenshot($filename = null) {
    static $count;
    $count += 1;
    $filename ??= sprintf('ss-%02d.png', $count);
    $this->page->screenshot()->saveToFile("tmp/{$filename}");
  }

  public function beforeAll() {
    //
  }

  public function afterAll() {
    //
  }

  public function visit($url) {
    $url = $url[0] === '/' ? self::$baseUrl . $url : $url;
    print "\n  " . str_shift($url, self::$baseUrl) . ' ... ';

    $navigation = $this->page->navigate($url);
    return $navigation->waitForNavigation();
  }

  public function get($code) {
    return $this->page->evaluate($code)->getReturnValue();
  }

  public function find($selector) {
    return $this->page->dom()->querySelector($selector);
  }

  public function name($name) {
    return $this->find('[name="' . $name . '"]');
  }

  public function click($selector) {
    print 'c';
    $this->find($selector)->click();
    return $this->within($selector);
  }

  public function link($selector, $timeout = 2000) {
    print "l";
    $evaluation = $this->page->evaluate("(() => { document.querySelector('{$selector}').click(); })()");
    $evaluation->waitForPageReload(Page::LOAD, $timeout); // ms
  }

  public function submit($selector, $timeout = 2000) {
    print "b";
    $evaluation = $this->page->evaluate("(() => { document.querySelector('{$selector}').click(); })()");
    $evaluation->waitForPageReload(Page::LOAD, $timeout); // ms
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
