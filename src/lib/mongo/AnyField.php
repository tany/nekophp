<?php
namespace mongo;

class AnyField {

  public static $pickupFields = ['_id', 'id', 'name', 'title', 'filename'];

  public static function collectFields($items) {
    $fields = [];
    foreach ($items as $item) {
      $fields = array_unique(array_merge(array_keys(array_clean($item->data())), $fields));
    }
    sort($fields);
    return array_intersect($fields, self::$pickupFields) + $fields;
  }

  public static function sortFields($item) {
    $fields = array_keys($item->data());
    sort($fields);

    //return array_merge(array_intersect($fields, self::$pickupFields) + $fields);
    return $fields;
  }
}
