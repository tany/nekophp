<?php
namespace mongo;

class AnyField extends Document {

  public static $pickupFields = ['_id', 'id', 'name', 'title', 'filename'];

  public $type;
  public $full;
  public $long;
  public $snip;

  public function __construct($data) {
    if (is_string($data) && preg_match('/^\d{4}-\d\d-\d\dT\d\d:\d\d:\d\d\+\d\d:\d\d$/', $data)) {
      $data = date_create($data);
    }
    $this->type = gettype($data);
    $this->full = $this->buildFull($data);
    $this->long = $this->buildLong($data);
    $this->snip = $this->buildSnip($data);
  }

  protected function buildFull($data) {
    return match (true) {
      $data === [] => '[ ]',
      is_null($data) || $data === '' => '',
      is_string($data) || is_numeric($data) => $data,
      is_bool($data) => var_export($data, true),
      is_array($data) => \mongo\format\JSON::encode($data),
      $data instanceof \DateTime => $data->format('Y-m-d H:i:s'),
      $data instanceof \MongoDB\BSON\Binary      => '*' . size_format(strlen($data->getData())),
      $data instanceof \MongoDB\BSON\Decimal128  => $data->__toString(),
      $data instanceof \MongoDB\BSON\Javascript  => $data->__toString(),
      $data instanceof \MongoDB\BSON\MaxKey      => 'maxKey',
      $data instanceof \MongoDB\BSON\MinKey      => 'minKey',
      $data instanceof \MongoDB\BSON\ObjectId    => $data->__toString(),
      $data instanceof \MongoDB\BSON\Regex       => $data->__toString(),
      $data instanceof \MongoDB\BSON\Timestamp   => date('Y-m-d H:i:s', $data->getIncrement() ?: $data->getTimestamp()),
      $data instanceof \MongoDB\BSON\UTCDateTime => $data->toDateTime()->format('Y-m-d\ H:i:s'),
      default => \mongo\format\JSON::encode($data)
    };
  }

  protected function buildLong($data, $digits = 100) {
    return match (true) {
      $data instanceof \DateTime => $data->format('Y-m-d H:i:s'),
      default => mb_strimwidth(preg_replace("/[\n\s]+/", ' ', $this->full), 0, $digits, '...')
    };
  }

  protected function buildSnip($data, $digits = 20) {
    return match (true) {
      $data === [] => '',
      $data instanceof \DateTime => $data->format('m/d H:i'),
      $data instanceof \MongoDB\BSON\ObjectId => substr_replace((string)$data, '...', 4, -4),
      $data instanceof \MongoDB\BSON\Timestamp => date('m/d H:i', $data->getIncrement() ?: $data->getTimestamp()),
      $data instanceof \MongoDB\BSON\UTCDateTime => $data->toDateTime()->format('m/d H:i'),
      default => mb_strimwidth(preg_replace("/[\n\s]+/", ' ', $this->long), 0, $digits, '...')
    };
  }

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
