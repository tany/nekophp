<?php
namespace elastic;

use \elastic\Document;
use \mongo\AnyField;
use \mongo\AnyValue;

class AnyDocument extends Document {

  public static function collectFields($items) {
    return AnyField::collectFields($items);
  }

  public static function sortFields($items) {
    return AnyField::sortFields($items);
  }

  public function values($name) {
    return new AnyValue($this->$name);
  }

  public function keywordSearch($str) {
    $str = trim(str_replace('　', ' ', $str));
    $str = preg_replace('/\p{Zs}+/u', ' ', $str);
    if (!$str) return $this;

    if (preg_match_all('/(\w+)\s?:\s*([^,:;\s]+)/', $str, $m)) {
      foreach ($m[1] as $idx => $field) {
        $this->must(['match_phrase' => [$field => $m[2][$idx]]]);
      }
      return $this;
    }
    if (preg_match('/\A[^,:;]+\z/', $str)) {
      return $this->where(['multi_match' => ['query' => $str, 'type' => 'phrase']]);
    }
    return $this->where(['match_none' => (object)[]]);
  }
}
