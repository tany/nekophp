<?php
namespace mongodb;

use \mongodb\Document;

class AnyDocument extends Document {

  public static $majorFields = ['_id', 'id', 'name', 'filename', 'title'];
  public static $searchFields = ['_id', 'name', 'filename', 'title', 'keywords', 'tags'];

  public static function collectFields($items) {
    $fields = [];
    foreach ($items as $item) {
      $fields = array_unique(array_merge(array_keys(array_clean($item->getData())), $fields));
    }
    sort($fields);
    return array_intersect($fields, self::$majorFields) + $fields;
  }

  public function sortFields() {
    $fields = array_keys($this->data);
    sort($fields);
    return array_merge(array_intersect($fields, self::$majorFields) + $fields);
  }

  public function textSearch($str) {
    if (!$str) return $this;
    $str = trim(str_replace('　', ' ', $str));
    $str = preg_replace('/\p{Zs}+/u', ' ', $str);

    if (preg_match_all('/(\w+)\s?:\s*([^,:;\s]+)/', $str, $m)) {
      foreach ($m[1] as $idx => $field) {
        $this->whereTextSearch($query['$and'][], $field, $m[2][$idx]);
      }
    } elseif (preg_match('/\A[^,:;\s]+\z/', $str)) {
      foreach (self::$searchFields as $field) {
        $this->whereTextSearch($query['$or'][], $field, $str);
      }
    }
    return $this->where($query ?? ['_id' => -1]);
  }

  protected function whereTextSearch(&$query, $field, $value) {
    if ($value === 'true' || $value === 'false') {
      $query[$field] = ($value === 'true');
    } elseif (ctype_digit($value)) {
      $query[$field] = (int)$value;
    } elseif (is_numeric($value)) {
      $query[$field] = (float)$value;
    } elseif (ctype_xdigit($value)) {
      $regex = str_replace('/', '\/', $value);
      $query['$where'] = "function() { return /{$regex}/.test(this.{$field}) }";
    } elseif (preg_match('/^\/.+\/$/', $value)) {
      $regex = preg_quote(substr($value, 1, -1), '/');
      $query['$where'] = "function() { return /{$regex}/.test(this.{$field}) }";
    } else {
      $query[$field] = ['$regex' => preg_quote($value)];
    }
  }

  public function getLinkId() {
    $id = $this->_id;
    if (is_scalar($id)) return $id;
    if (is_object($id) && method_exists($id, '__toString')) return $id;
    return null;
  }

  public function collectValues($field) {
    $data = $this->$field;
    $type = $this->getType($data);
    $full = $this->toString($data);
    $long = $this->toLongString($data, $full);
    $short = $this->toShortString($data, $long);

    return ['type' => $type, 'full' => $full, 'long' => $long, 'short' => $short];
  }

  public function getType($data) {
    $type = gettype($data);
    if (is_object($data)) $type .= ' ' . str_rshift($data::class, '\\');
    return $type;
  }

  public function toString($data) {
    return match (true) {
      $data === [] => '[ ]',
      is_null($data) || $data === '' => '',
      is_string($data) || is_numeric($data) => $data,
      is_bool($data) => var_export($data, true),
      is_array($data) => \mongodb\format\JSON::encode($data),
      $data instanceof \MongoDB\BSON\Binary      => '*' . size_format(strlen($data->getData())),
      $data instanceof \MongoDB\BSON\Decimal128  => $data->__toString(),
      $data instanceof \MongoDB\BSON\Javascript  => $data->__toString(),
      $data instanceof \MongoDB\BSON\MaxKey      => 'maxKey',
      $data instanceof \MongoDB\BSON\MinKey      => 'minKey',
      $data instanceof \MongoDB\BSON\ObjectId    => $data->__toString(),
      $data instanceof \MongoDB\BSON\Regex       => $data->__toString(),
      $data instanceof \MongoDB\BSON\Timestamp   => date('Y-m-d H:i:s', $data->getIncrement() ?: $data->getTimestamp()),
      $data instanceof \MongoDB\BSON\UTCDateTime => $data->toDateTime()->format('Y-m-d\ H:i:s'),
      default => \mongodb\format\JSON::encode($data)
    };
  }

  public function toLongString($data, $str, $digits = 100) {
    return match (true) {
      $data === [] => '',
      default => mb_strimwidth(preg_replace("/[\n\s]+/", ' ', $str), 0, $digits, '...')
    };
  }

  public function toShortString($data, $str, $digits = 20) {
    return match (true) {
      $data === [] => '',
      $data instanceof \MongoDB\BSON\ObjectId => substr_replace((string)$data, '...', 4, -4),
      $data instanceof \MongoDB\BSON\Timestamp => date('m/d H:i', $data->getIncrement() ?: $data->getTimestamp()),
      $data instanceof \MongoDB\BSON\UTCDateTime => $data->toDateTime()->format('m/d H:i'),
      default => mb_strimwidth(preg_replace("/[\n\s]+/", ' ', $str), 0, $digits, '...')
    };
  }
}
