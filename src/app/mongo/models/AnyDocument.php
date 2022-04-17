<?php
namespace mongo\models;

use \core\utils\form\Keyword;
use \mongo\models\Document;
use \mongo\field\ObjectId;

class AnyDocument extends Document {

  public static $pickupFields = ['_id', 'id', 'name', 'title', 'filename'];
  public static $searchFields = ['_id', 'name', 'filename', 'title', 'keywords', 'tags'];

  public function cellFields($items) {
    foreach ($items as $item) {
      $fields[] = array_keys(array_filter_r($item->data(), 'object_present'));
    }
    $fields = array_sort(array_unique(array_merge(...$fields ?? [])));
    return array_intersect($fields, self::$pickupFields) + $fields;
  }

  public function showFields() {
    return array_sort(array_keys($this->data()));
  }

  public function sortValues() {
    $this->_data = array_ksort($this->_data);
    return $this;
  }

  public function dbValue($name) {
    return new AnyValue($this->$name);
  }

  public function hrefId() {
    $id = $this->_id;
    if (is_scalar($id)) return $id;
    if (is_object($id) && method_exists($id, '__toString')) return $id;
    return null;
  }

  public function keywordSearch($str) {
    $input = Keyword::parse($str)->cast();
    if (!$input->count()) return $this;

    foreach ($input->index as $value) {
      foreach (self::$searchFields as $field) {
        $this->setKeywordQuery($query['$or'][], $field, $value);
      }
    }
    foreach ($input->assoc as $field => $value) {
      $this->setKeywordQuery($query['$and'][], $field, $value);
    }
    return $this->where($query ?? ['_id' => -1]);
  }

  protected function setKeywordQuery(&$query, $field, $value) {
    if (is_float($value)) {
      $query[$field] = new \MongoDB\BSON\Decimal128($value);
    } elseif (ObjectId::isObjectId($value)) {
      $query['$where'] = "function() { return /{$value}/.test(this.{$field}) }";
    } elseif ($value instanceof \DateTime) {
      $ts = new \MongoDB\BSON\Timestamp($value->format('U'), 0);
      $utc = new \MongoDB\BSON\UTCDateTime($value->format('U') * 1000);
      $query[$field] = ['$in' => [$ts, $utc]];
    } elseif ($value instanceof \Regexp) {
      $query['$where'] = "function() { return {$value->pattern}.test(this.{$field}) }";
      dump($query['$where']);
    } else {
      $query[$field] = is_string($value) ? ['$regex' => preg_quote($value)] : $value;
    }
  }
}
