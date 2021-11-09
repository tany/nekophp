<?php
namespace mongo;

class AnyValue {

  public $type;
  public $full;
  public $cell;
  public $tips;

  public function __construct($data) {
    if (is_string($data) && preg_match('/^\d{4}-\d\d-\d\dT\d\d:\d\d:\d\d\+\d\d:\d\d$/', $data)) {
      $data = date_create($data);
    }
    $this->type = strtolower(gettype($data));
    $this->full = $this->toFull($data);
    $this->cell = $this->toCell($data);

    $tips = $this->toTips($data);
    $this->tips = mb_strwidth($tips) > 14 ? $tips : null;
  }

  protected function setTimezone($date) {
    static $tz;
    $tz ??= new \DateTimeZone(date_default_timezone_get());
    return $date->setTimezone($tz);
  }

  protected function toFull($data) {
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
      $data instanceof \MongoDB\BSON\UTCDateTime => $this->setTimezone($data->toDateTime())->format('Y-m-d\ H:i:s'),
      default => \mongo\format\JSON::encode($data)
    };
  }

  protected function toCell($data, $digits = 20) {
    return match (true) {
      $data === [] => '',
      $data instanceof \DateTime => $data->format('m/d H:i'),
      $data instanceof \MongoDB\BSON\ObjectId => substr_replace((string)$data, '...', 4, -4),
      $data instanceof \MongoDB\BSON\Timestamp => date('m/d H:i', $data->getIncrement() ?: $data->getTimestamp()),
      $data instanceof \MongoDB\BSON\UTCDateTime => $this->setTimezone($data->toDateTime())->format('m/d H:i'),
      default => mb_strimwidth(preg_replace("/[\n\s]+/", ' ', $this->full), 0, $digits, '...')
    };
  }

  protected function toTips($data, $digits = 100) {
    return match (true) {
      $data instanceof \DateTime => $data->format('Y-m-d H:i:s'),
      default => mb_strimwidth(preg_replace("/[\n\s]+/", ' ', $this->full), 0, $digits, '...')
    };
  }
}
