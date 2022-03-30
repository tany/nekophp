<?php
namespace mongo\model;

use \mongo\model\Document;
use \mongo\field\ObjectId;

class AnyDocument extends Document {

  public static $searchFields = ['_id', 'name', 'filename', 'title', 'keywords', 'tags'];

  public static function collectFields($items) {
    return AnyField::collectFields($items);
  }

  public static function sortFields($item) {
    return AnyField::sortFields($item);
  }

  public static function sortValues($item) {
    return array_ksort($item->data());
  }

  public function values($name) {
    return new AnyValue($this->$name);
  }

  public function hrefId() {
    $id = $this->_id;
    if (is_scalar($id)) return $id;
    if (is_object($id) && method_exists($id, '__toString')) return $id;
    return null;
  }

  public function keywordSearch($str) {
    $str = trim(str_replace('　', ' ', $str));
    $str = preg_replace('/\p{Zs}+/u', ' ', $str);
    if (!$str) return $this;

    if (preg_match_all('/(\w+)\s?:\s*([^,:;\s]+)/', $str, $m)) {
      foreach ($m[1] as $idx => $field) {
        $this->keywordWhere($query['$and'][], $field, $m[2][$idx]);
      }
    } elseif (preg_match('/\A[^,:;\s]+\z/', $str)) {
      foreach (self::$searchFields as $field) {
        $this->keywordWhere($query['$or'][], $field, $str);
      }
    }
    return $this->where($query ?? ['_id' => -1]);
  }

  protected function keywordWhere(&$query, $field, $value) {
    if ($value === 'true' || $value === 'false') {
      $query[$field] = ($value === 'true');
    } elseif (ctype_digit($value)) {
      $query[$field] = (int)$value;
    } elseif (is_numeric($value)) {
      $query[$field] = (float)$value;
    } elseif (ObjectId::isObjectId($value)) {
      $query['$where'] = "function() { return /{$value}/.test(this.{$field}) }";
    } elseif (preg_match('/^\/.+\/$/', $value)) {
      $regex = preg_quote(substr($value, 1, -1), '/');
      $query['$where'] = "function() { return /{$regex}/.test(this.{$field}) }";
    } else {
      $query[$field] = ['$regex' => preg_quote($value)];
    }
  }
}
