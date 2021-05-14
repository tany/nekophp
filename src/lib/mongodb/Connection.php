<?php
namespace mongodb;

use \MongoDB\Driver\Manager;
use \MongoDB\Driver\Command;
use \MongoDB\Driver\Query;
use \MongoDB\Driver\BulkWrite;

/**
 * @see https://github.com/mongodb/mongo-php-library/blob/master/src/Client.php
 * @see https://github.com/mongodb/mongo-php-library/blob/master/src/Operation/ListDatabases.php
 */
final class Connection {

  public static $lastConnection;
  public static $lastQuery;

  public $hostname;
  public $manager;
  public $error;
  public $result; // MongoDB\Driver\WriteResult

  public function __construct($conf) {
    $conf = self::parseSetting($conf);
    $this->hostname = str_pop($conf['hostname'], ',', ':');
    $this->manager = new Manager("mongodb://{$conf['hostname']}", $conf['uriOptions'], $conf['driverOptions']);
  }

  public static function parseSetting($conf) {
    if (is_array($conf)) return $conf;

    $data = parse_url($conf);
    $port = $data['port'] ?? '';
    $host = $data['host'] . ($port ? ":{$port}" : '');
    $path = ltrim($data['path'] ?? '', '/');
    $user = $data['user'] ?? null;
    $pass = $data['pass'] ?? null;
    $options = $user ? ['username' => $user, 'password' => $pass] : [];
    $options = array_merge($options, qs_parse($data['options'] ?? ''));
    if ($user) $options['authSource'] ??= 'admin';
    return ['hostname' => $host, 'database' => $path, 'uriOptions' => $options, 'driverOptions' => $options];
  }

  public static function connect($name, $uri) {
    static $cache;
    return $cache[$name] ??= new self($uri);
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

  // Query

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
