<?php
namespace core\utils\html;

use \core\Core;

class Pager {

  public static $defaults = [
    'param' => 'p',
    'limit' => 40,
    'width' => 3,
  ];

  public $param;
  public $limit;
  public $width;
  public $total;
  public $page;
  public $link;
  public $skip;

  /**
   * @param int|array $items
   * @param int $options['total']
   * @param int $options['limit']
   * @param int $options['width']
   * @param string $options['param']
   * @param string $options['link']
   */
  public function __construct($options = []) {
    $this->total = $options['total'] ?? null;
    $this->param = $options['param'] ?? self::$defaults['param'];
    $this->limit = $options['limit'] ?? self::$defaults['limit'];
    $this->width = $options['width'] ?? self::$defaults['width'];

    $this->page = (int)($options['page'] ?? Core::$request->{$this->param} ?? 1);
    $this->link = $options['link'] ?? Core::$request->url();
    $this->skip = ($this->page - 1) * $this->limit; // for db
  }

  public function total($val) {
    $this->total = is_int($val) ? $val : count($val);
    return $this;
  }

  public function limit($val) {
    $this->limit = $val;
    return $this;
  }

  public function page($val) {
    $this->page = $val;
    return $this;
  }

  protected function calculate() {
    $total = $this->total;
    $limit = $this->limit;
    $width = $this->width;
    $page = $this->page;

    $last = (int)ceil($total / $limit) ?: 1;
    $prev = ($page > 1) ? $page - 1 : 0;
    $next = ($page < $last) ? $page + 1 : 0;

    $snum = $page - $width;
    $snum = ($snum > 0) ? $snum : 1;
    $enum = $page + $width;
    $enum = ($enum > $last) ? $last : $enum;
    if ($enum - $snum < $width * 2 + 1) $enum = min($last, $width * 2 + $snum);
    if ($enum - $snum < $width * 2 + 1) $snum = max(1, $enum - $width * 2);

    return [$page, $last, $prev, $next, $snum, $enum];
  }

  public function __toString() {
    [$page, $last, $prev, $next, $snum, $enum] = $this->calculate();

    for ($i = $snum; $i <= $enum; $i++) {
      $h[] = ($i === $page)
        ? self::li($i, 0, 'active')
        : self::li($i, $i, 'inactive d-none d-sm-block');
    }

    if ($snum > 1) {
      $h[0] = self::li(1, 1, 'inactive d-none d-sm-block');
      $h[1] = self::li(lc('--pager.gap'), 0, 'disabled gap d-none d-sm-block');
    }
    if ($enum < $last) {
      array_splice($h, -2);
      $h[] = self::li(lc('--pager.gap'), 0, 'disabled gap d-none d-sm-block');
      $h[] = self::li($last, $last, 'inactive d-none d-sm-block');
    }

    $prev = $prev
      ? self::li(lc('--pager.prev'), $prev, 'prev')
      : self::li(lc('--pager.prev'), 0, 'disabled prev');

    $next = $next
      ? self::li(lc('--pager.next'), $next, 'next')
      : self::li(lc('--pager.next'), 0, 'disabled next');

    return '<ul class="pagination justify-content-center">' . $prev . join($h ?? []) . $next . '</ul>';
  }

  protected function li($name, $page = 0, $class = '') {
    if (is_numeric($name)) $name = number_format($name);
    if ($class) $class = ' '. $class;
    if ($page) $href = ' href="' . url_merge_query($this->link, [$this->param => $page]) . '"';

    return '<li class="page-item' . $class . '">'
      . '<a class="page-link"' . ($href ?? '') .'>' . $name . '</a></li>';
  }
}
