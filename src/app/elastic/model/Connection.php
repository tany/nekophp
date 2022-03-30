<?php
namespace elastic\model;

use \Elasticsearch\ClientBuilder;
use \Elasticsearch\Common\Exceptions\Missing404Exception;

final class Connection {

  public static $lastConnection;

  public $client;
  public $name;

  public function __construct($conf) {
    $this->client = ClientBuilder::create()->setHosts($conf)->build();
    $this->name = preg_replace('/.*(@|\/\/)(.*?)(\/|:|$).*/', '$2', $conf[0]);
  }

  public static function connect($name, $conf) {
    static $cache;
    return $cache[$name] ??= new self($conf);
  }

  public static function listClients() {
    foreach (setting('elastic.clients') as $key => &$conf) {
      $conf['name'] = "<{$key}> " . preg_replace('/.*(@|\/\/)(.*)/', '$2', $conf[0]);
      $clients[$key] = $conf;
    }
    return $clients ?? [];
  }

  // Index

  public function catIndices() {
    foreach ($this->client->cat()->indices(['s' => 'index', 'pri' => true]) as $data) {
      $array[] = (object) [
        'name' => $data['index'],
        'health' => ucfirst($data['health']),
        'status' => ucfirst($data['status']),
        'docs' => $data['docs.count'],
        'size' => strtoupper(preg_replace('/([0-9])([a-z])/', '$1 $2', $data['pri.store.size'])),
      ];
    }
    return $array ?? [];
  }

  public function createIndex($index) {
    return $this->client->indices()->create(['index' => $index]);
  }

  public function deleteIndex($index) {
    return $this->client->indices()->delete(['index' => $index]);
  }

  public function reindex($src, $dst) {
    $params = [
      'body' => [
        'source' => ['index' => $src],
        'dest' => ['index' => $dst],
      ],
    ];
    $results = $this->client->reindex($params);
    return $results['created'];
  }

  // Document

  public function get($params) {
    self::$lastConnection = $this;

    try {
      return $this->client->get($params);
    } catch (Missing404Exception $e) {
      return null;
    }
  }
}
