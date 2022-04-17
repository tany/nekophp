<?php
namespace elastic\models;

use \core\utils\form\Keyword;
use \elastic\models\Document;
use \elastic\models\AnyValue;

class AnyDocument extends Document {

  public static $pickupFields = ['_id', 'id', 'name', 'title', 'filename'];

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

  public function keywordSearch($str) {
    $input = Keyword::parse($str)->cast();
    if (!$input->count()) return $this;

    foreach ($input->index as $value) {
      if ($value instanceof \DateTime) $value = $value->format('c');
      if ($value instanceof \Regexp)  $value = $value->source;

      $this->where(['multi_match' => ['query' => $value, 'type' => 'phrase']]);
    }
    foreach ($input->assoc as $field => $value) {
      if ($value instanceof \DateTime) $value = $value->format('c');
      if ($value instanceof \Regexp) $value = $value->source;

      $this->must(['match_phrase' => [$field => $value]]);
    }
    return $this;
    // return $this->where(['match_none' => (object)[]]);
  }
}
