<?php
namespace core\utils;

use \core\Core;

class Pager {

  public static $defaults = [
    'param' => 'page',
    'width' => 3,
    'limit' => 10,
  ];

  public $param;
  public $width;
  public $limit;
  public $page;
  public $link;
  public $size;
  public $skip;

  /**
   * @param int|array $items
   * @param array $options [param:, width:, limit:, page:, link:]
   */
  public function __construct($items, $options = []) {
    $this->param = $options['param'] ?? self::$defaults['param'];
    $this->width = $options['width'] ?? self::$defaults['width'];
    $this->limit = $options['limit'] ?? self::$defaults['limit'];

    $request = Core::$request;
    $this->page = (int)($options['page'] ?? $request->{$this->param} ?? 1);
    $this->link = $options['link'] ?? $request->getUrl();
    $this->size = is_int($items) ? $items : count($items);
    $this->skip = ($this->page - 1) * $this->limit; // for db
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
    $width = $this->width;
    $limit = $this->limit;
    $page = $this->page;
    $size = $this->size;

    $last = (int)ceil($size / $limit) ?: 1;
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
      $h[1] = self::li(lc('core.pager.gap'), 0, 'disabled gap d-none d-sm-block');
    }
    if ($enum < $last) {
      array_splice($h, -2);
      $h[] = self::li(lc('core.pager.gap'), 0, 'disabled gap d-none d-sm-block');
      $h[] = self::li($last, $last, 'inactive d-none d-sm-block');
    }

    $prev = $prev
      ? self::li(lc('core.pager.prev'), $prev, 'prev')
      : self::li(lc('core.pager.prev'), 0, 'disabled prev');

    $next = $next
      ? self::li(lc('core.pager.next'), $next, 'next')
      : self::li(lc('core.pager.next'), 0, 'disabled next');

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
