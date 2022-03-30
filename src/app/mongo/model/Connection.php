<?php
namespace mongo\model;

use \MongoDB\Driver\Manager;
use \MongoDB\Driver\Command;
use \MongoDB\Driver\Query;
use \MongoDB\Driver\BulkWrite;

final class Connection {

  public static $lastConnection;
  public static $lastQuery;

  public $manager;
  public $name;
  public $result; // MongoDB\Driver\WriteResult

  public function __construct($conf) {
    $this->manager = new Manager($conf['uri'], $conf['uriOptions'] ?? [], $conf['driverOptions'] ?? []);
    $this->name = preg_replace('/.*(@|\/\/)(.*?)(\/|:|$).*/', '$2', $conf['uri']);
  }

  public static function connect($name, $conf) {
    static $cache;
    return $cache[$name] ??= new self($conf);
  }

  public static function listClients() {
    foreach (setting('mongo.clients') as $key => &$conf) {
      $conf['name'] = "<{$key}> " . preg_replace('/.*(@|\/\/)(.*?)(\/.*|$)/', '$2', $conf['uri']);
      $clients[$key] = $conf;
    }
    return $clients ?? [];
  }

  public function command($db, $command) {
    return $this->manager->executeCommand($db, new Command($command));
  }

  public function adminCommand($command) {
    return $this->manager->executeCommand('admin', new Command($command));
  }

  // Database

  public function databases() {
    $cursor = $this->adminCommand(['listDatabases' => 1]);
    $array  = current($cursor->toArray())->databases;
    usort($array, fn($a, $b) => $a->name <=> $b->name);
    return $array;
  }

  public function dropDatabase($db) {
    return $this->command($db, ['dropDatabase' => 1]);
  }

  // Collection

  public function collections($db, $options = []) {
    $cursor = $this->command($db, ['listCollections' => 1]);
    $array  = $cursor->toArray();
    usort($array, fn($a, $b) => $a->name <=> $b->name);

    if ($options['size'] ?? false) {
      foreach ($array as $coll) $coll->dataSize = $this->collectionSize("{$db}.{$coll->name}");
    }
    return $array;
  }

  public function collectionSize($namespace) {
    return $this->adminCommand(['dataSize' => $namespace])->toArray()[0];
  }

  public function dropCollection($db, $collection) {
    return $this->command($db, ['drop' => $collection]);
  }

  // Document

  public function query($namespace, $query, $options = []) {
    self::$lastConnection = $this;
    self::$lastQuery = ['namespace' => $namespace, 'query' => $query, 'options' => $options];
    return $this->manager->executeQuery($namespace, new Query($query, $options));
  }

  public function write($namespace, $bulk) {
    return $this->manager->executeBulkWrite($namespace, $bulk);
  }

  public function insert($namespace, $data = []) {
    $bulk = new BulkWrite;
    $oid = $bulk->insert($data) ?? $data['_id'];
    $this->result = $this->write($namespace, $bulk);
    return $this->result->getWriteErrors() ? false : $oid;
  }

  public function update($namespace, $query = [], $update = [], $options = []) {
    $bulk = new BulkWrite;
    $bulk->update($query, $update, $options);
    $this->result = $this->write($namespace, $bulk);
    return !$this->result->getWriteErrors();
  }

  public function delete($namespace, $query = [], $options = []) {
    $bulk = new BulkWrite;
    $bulk->delete($query, $options);
    $this->result = $this->write($namespace, $bulk);
    return !$this->result->getWriteErrors();
  }

  public function upsert($namespace, $query = [], $update = [], $options = []) {
    return $this->update($namespace, $query, $update, $options + ['upsert' => true]);
  }

  public function updateAll($namespace, $query = [], $update = [], $options = []) {
    $this->update($namespace, $query, $update, $options + ['multi' => true]);
    return $this->result->getModifiedCount();
  }

  public function deleteAll($namespace, $query = [], $options = []) {
    $this->delete($namespace, $query, $options + ['limit' => 0]);
    return $this->result->getDeletedCount();
  }
}
