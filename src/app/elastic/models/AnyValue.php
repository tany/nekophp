<?php
namespace elastic\models;

use \core\utils\format\JSON;

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
      $data instanceof \DateTime => $data->format('m/d H:i'),
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
      $data instanceof \DateTime => $data->format('Y-m-d H:i:s'),
      default => JSON::encode($data)
    };
  }
}
