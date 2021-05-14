<?php
namespace mongodb\format;

class JSON {

  public static function encode($data) {
    if ($data === null) return null;
    if ($data === []) return null;

    $data = array_map_recursive([self::class, 'encodeObject'], $data);
    return json_encode_pretty($data);
  }

  public static function encodeObject($item) {
    return match (true) {
      $item instanceof \MongoDB\BSON\Binary => ['$binary' => $item->getData(), '$type' => $item->getType()],
      default => $item,
    };
  }

  public static function decode($json) {
    if (!preg_match('/\A\s*\{/s', $json)) $json = "{ {$json} }";
    $json = preg_replace('/,\s*\}\s*$/s', ' }', $json);
    //$json = preg_replace('/([\'"])?([\w]+)([\'"])?: +/', '"$2": ', $json);

    $data = json_decode($json, true);
    if (!is_array($data)) throw new \Exception('JSON Decode Error');

    return self::decodeObject($data);
  }

  public static function decodeObject(&$data) {
    foreach ($data as $key => &$item) {
      if (!is_array($item)) continue;

      $data[$key] = match (true) {
        isset($item['$binary']) => new \MongoDB\BSON\Binary($item['$binary'], $item['$type']),
        isset($item['$numberDecimal']) => new \MongoDB\BSON\Decimal128($item['$numberDecimal']),
        isset($item['$code']) => new \MongoDB\BSON\Javascript($item['$code'], $item['$scope'] ?? null),
        isset($item['$maxKey']) => new \MongoDB\BSON\MaxKey(),
        isset($item['$minKey']) => new \MongoDB\BSON\MinKey(),
        isset($item['$oid']) => new \MongoDB\BSON\ObjectId($item['$oid']),
        isset($item['$regex']) => new \MongoDB\BSON\Regex($item['$regex'], $item['$options'] ?? ''),
        isset($item['$timestamp']) => new \MongoDB\BSON\Timestamp($item['$timestamp']['i'], $item['$timestamp']['t']),
        isset($item['$date']) => new \MongoDB\BSON\UTCDateTime($item['$date']['$numberLong']),
        default => self::decodeObject($item),
      };
    }
    return $data;
  }
}
