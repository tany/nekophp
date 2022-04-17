<?php
namespace core\utils\form;

class Keyword {

  public $index;
  public $assoc;

  public function __construct($index, $assoc) {
    $this->index = $index;
    $this->assoc = $assoc;
  }

  public static function parse($str) {
    $str = trim(preg_replace('/\p{Zs}+/u', ' ', $str));
    if (blank($str)) return new self([], []);

    $assoc = [];
    $str = preg_replace_callback('/(\w+)\s?:\s*(.+)(\s|$)/', function($m) use (&$assoc) {
      $assoc[$m[1]] = $m[2];
      return '';
    }, $str);

    $index = array_unique(array_presence(preg_split('/\s+/', $str)));

    return new self($index, $assoc);
  }

  public function count() {
    return count($this->index) + count($this->assoc);
  }

  public function cast() {
    foreach ($this->index as &$val) $val = $this->castValue($val);
    foreach ($this->assoc as &$val) $val = $this->castValue($val);
    return $this;
  }

  protected function castValue($str) {
    if (is_string($str)) {
      if ($val = preg_filter('/^"(.*)"$/', '$1', $str)) return $val;
      if (preg_match('|^/.+/[a-z]*$|', $str)) return new \Regexp($str);
    }
    if ($date = date_create($str)) return $date;

    return match (true) {
      $str === 'true' => true,
      $str === 'false' => false,
      ctype_digit($str) =>(int)$str,
      is_numeric($str) => (float)$str,
      default => $str
    };
  }
}
