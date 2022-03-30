<?php
namespace elastic\feature;

use \core\utils\Pager;
use \elastic\model\Cursor;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/full-text-queries.html
 */
trait Queryable {

  protected $_queryParams = [
    'index' => null,
    // 'sort' => ['_seq_no:desc'],
  ];
  protected $_queryTotal;

  public function where($query) {
    $this->_queryParams['body']['query'] ??= [];
    $this->_queryParams['body']['query'] += $query;
    return $this;
  }

  public function must($query) {
    $this->_queryParams['body']['query']['bool']['must'][] = $query;
    return $this;
  }

  public function filter($query) {
    $this->_queryParams['body']['query']['bool']['filter'][] = $query;
    return $this;
  }

  public function should($query) {
    $this->_queryParams['body']['query']['bool']['should'][] = $query;
    return $this;
  }

  public function mustNot($query) {
    $this->_queryParams['body']['query']['bool']['must_not'][] = $query;
    return $this;
  }

  public function sort($val) {
    $this->_queryParams['sort'] = $val;
    return $this;
  }

  public function skip($val) {
    $this->_queryParams['from'] = $val;
    return $this;
  }

  public function limit($val) {
    $this->_queryParams['size'] = $val;
    return $this;
  }

  public function total() {
    return $this->_queryTotal;
  }

  public function buildQuery() {
    $this->_queryParams['index'] ??= $this->_index;
    return $this->_queryParams;
  }

  public function find($id) {
    $params = ['id' => $id] + $this->buildQuery();
    $results = $this->connection()->get($params);
    return $results ? (new static())->resultsUnserialize($results, $this->_connection) : null;
  }

  public function findAll() {
    $params = $this->buildQuery();
    $results = $this->connection()->client->search($params);
    $this->_queryTotal = $results['hits']['total']['value'];

    foreach ($results['hits']['hits'] as $data) {
      $items[] = (new static())->resultsUnserialize($data, $this->_connection);
    }
    return $items ?? [];
  }

  public function findOne() {
    $params = $this->buildQuery() + ['from' => 0, 'size' => 1];
    $results = $this->connection()->client->search($params);
    $this->_queryTotal = $results['hits']['total']['value'];

    $data = $results['hits']['hits'][0] ?? null;
    return $data ? (new static())->resultsUnserialize($data, $this->_connection) : null;
  }

  public function paginate($options = []) {
    $options['limit'] ??= $this->_queryParams['size'] ?? null;

    $pager = new Pager($options);
    $this->_queryParams['from'] = $pager->skip;
    $this->_queryParams['size'] = $pager->limit;

    $items = $this->findAll();
    $pager->total($this->total());
    $cursor = new Cursor($items);
    $cursor->pager = $pager;
    return $cursor;
  }

  public function save() {
    $data = array_filter_recursive($this->_data, 'present');
    $params = ['index' => $this->_index, 'id' => $this->_id, 'refresh' => true, 'body' => $data];
    $results = $this->connection()->client->index($params);
    if (!preg_match('/^(created|updated|noop)$/', $results['result'])) return false;

    $this->_index ??= $results['_index'];
    $this->_type ??= $results['_type'];
    $this->_id ??= $results['_id'];
    return true;
  }

  public function delete() {
    $params = ['index' => $this->_index, 'id' => $this->_id, 'refresh' => true];
    $results = $this->connection()->client->delete($params);
    return $results['result'] === 'deleted';
  }

  public function updateAll($params) {
    $params = $this->buildQuery() + ['refresh' => true];
    $results = $this->connection()->client->updateByQuery($params);
    return $results['updated'] ?? 0;
  }

  public function deleteAll() {
    $params = $this->buildQuery() + ['refresh' => true];
    $results = $this->connection()->client->deleteByQuery($params);
    return $results['deleted'] ?? 0;
  }
}
