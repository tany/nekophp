<?php
namespace mongo\models;

use \mongo\utils\format\JSON;

class AnyValue {

  public $type;
  public $cell;
  public $tips;
  public $long;

  public function __construct($data) {
    $this->setType($data);
    $this->setLong($data);
    $this->setCell($data);
    $this->setTips($data);
  }

  protected function setType($data) {
    $this->type = strtolower(str_rshift(get_debug_type($data), '\\'));
  }

  protected function setCell($data) {
    if (is_string($data)) $date = date_create_from_iso($data);

    $this->cell = match (true) {
      !empty($date) => $date->format('m/d H:i'),
      $data instanceof \MongoDB\BSON\ObjectId => substr_replace((string)$data, '...', 4, -4),
      $data instanceof \MongoDB\BSON\Timestamp => date('m/d H:i', $data->getIncrement() ?: $data->getTimestamp()),
      $data instanceof \MongoDB\BSON\UTCDateTime => $this->setTimezone($data->toDateTime())->format('m/d H:i'),
      default => mb_strimwidth($this->long, 0, 40, '...')
    };
  }

  protected function setTips($data) {
    $this->tips = mb_strimwidth($this->long, 0, 100, '...');

    if (mb_strwidth($this->tips) < 15) $this->tips = null;
  }

  protected function setLong($data) {
    $this->long = match (true) {
      ($data === '') => '',
      is_null($data) => '',
      is_string($data) => $data,
      $data instanceof \MongoDB\BSON\Binary      => '*' . size_format(strlen($data->getData())),
      $data instanceof \MongoDB\BSON\Decimal128  => $data->__toString(),
      $data instanceof \MongoDB\BSON\Javascript  => $data->__toString(),
      $data instanceof \MongoDB\BSON\MaxKey      => 'maxKey',
      $data instanceof \MongoDB\BSON\MinKey      => 'minKey',
      $data instanceof \MongoDB\BSON\ObjectId    => $data->__toString(),
      $data instanceof \MongoDB\BSON\Regex       => $data->__toString(),
      $data instanceof \MongoDB\BSON\Timestamp   => date('Y-m-d H:i:s', $data->getIncrement() ?: $data->getTimestamp()),
      $data instanceof \MongoDB\BSON\UTCDateTime => $this->setTimezone($data->toDateTime())->format('Y-m-d\ H:i:s'),
      default => JSON::encode($data)
    };
  }

  protected function setTimezone($date) {
    static $tz;
    $tz ??= new \DateTimeZone(date_default_timezone_get());
    return $date->setTimezone($tz);
  }
}
