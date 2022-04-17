<?php
namespace mongo\utils\format;

use \mongo\features\Hash;

class JSON extends \core\utils\format\JSON {

  public static function encodeObject($item) {
    return match (true) {
      $item instanceof \MongoDB\BSON\Binary => ['$binary' => $item->getData(), '$type' => $item->getType()],
      default => $item,
    };
  }

  public static function decodeObject($item) {
    if (!is_object($item)) return $item;

    return match (true) {
      isset($item->{'$binary'}) => new \MongoDB\BSON\Binary($item->{'$binary'}, $item->{'$type'}),
      isset($item->{'$numberDecimal'}) => new \MongoDB\BSON\Decimal128($item->{'$numberDecimal'}),
      isset($item->{'$code'}) => new \MongoDB\BSON\Javascript($item->{'$code'}, $item->{'$scope'} ?? null),
      isset($item->{'$maxKey'}) => new \MongoDB\BSON\MaxKey(),
      isset($item->{'$minKey'}) => new \MongoDB\BSON\MinKey(),
      isset($item->{'$oid'}) => new \MongoDB\BSON\ObjectId($item->{'$oid'}),
      isset($item->{'$regex'}) => new \MongoDB\BSON\Regex($item->{'$regex'}, $item->{'$options'} ?? ''),
      isset($item->{'$timestamp'}) => new \MongoDB\BSON\Timestamp($item->{'$timestamp'}->i, $item->{'$timestamp'}->t),
      isset($item->{'$date'}) => new \MongoDB\BSON\UTCDateTime($item->{'$date'}->{'$numberLong'}),
      default => (object)array_map_r($item, [self::class, 'decodeObject'])
    };
  }
}
