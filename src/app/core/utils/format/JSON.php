<?php
namespace core\utils\format;

class JSON {

  public static function encode($data) {
    if (is_array($data)) $data = self::encodeObjects($data);
    return json_encode_pretty($data);
  }

  public static function encodeObjects($items) {
    return array_map_r($items, [static::class, 'encodeObject']);
  }

  public static function encodeObject($item) {
    return $item;
  }

  public static function decode($json) {
    if ($json === '') return null;

    $json = preg_replace('/,\s*\}\s*$/s', ' }', $json);
    $data = (array)json_decode($json, false, 512, JSON_THROW_ON_ERROR);
    // if (!$data) throw new \Exception('JSON Decode Error');

    return self::decodeObjects($data);
  }

  public static function decodeObjects($items) {
    return array_map_r($items, [static::class, 'decodeObject']);
  }

  public static function decodeObject($item) {
    if (!is_object($item)) return $item;

    return new \Hash(array_map_r($item, [self::class, __FUNCTION__]));
  }
}
