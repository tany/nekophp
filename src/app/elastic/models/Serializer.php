<?php
namespace elastic\models;

use \Elasticsearch\Serializers\SerializerInterface;
use \core\utils\format\JSON;

class Serializer implements SerializerInterface {

  /**
   * Serialize a complex data-structure into a json encoded string
   *
   * @param  mixed $data The data to encode
   * @return string
   */
  public function serialize($data): string {
    return JSON::encode($data);
  }

  /**
   * Deserialize json encoded string into an associative array
   *
   * @param  string $data    JSON encoded string
   * @param  array  $headers Response Headers
   * @return string|array
   */
  public function deserialize(?string $data, array $headers) {
    if ($data === null) return null;

    return JSON::decode($data);
  }
}
