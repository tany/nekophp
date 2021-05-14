<?php
namespace core\actions;

use \core\feature\AccessorClass;

class Tests extends \core\Action {

  use \core\actions\LoginFilter;

  public static $partials = [
    'test' => 'test-test',
    'append' => 'test',
  ];

  public function index($request) {
    $item = new AccessorClass(['name' => '<s>ねーむ</s>']);
    $this->items = [$item];
  }

  public function sendData() {
    $data = 'Test Data';
    return $this->response->sendData($data)->setContentType('text/plain');
  }

  public function sendJson() {
    $data = ['message' => 'Test Data'];
    return $this->response->sendJson($data);
  }

  public function sendLocation() {
    $this->alert('This is a Test!', 'danger');
    return $this->alert('Redirect Success')->locate();
  }

  public function downloadData() {
    $data = file_read(ROOT . '/README.md');
    return $this->response->sendData($data, 'README_DATA.md');
  }

  public function downloadJson() {
    $data = ['message' => 'Test Data'];
    return $this->response->sendJson($data, 'README.json');
  }

  public function downloadFile() {
    $file = ROOT . '/README.md';
    return $this->response->sendFile($file, 'README_FILE.md');
  }

  public function abort403() {
    abort(403);
  }

  public function abort404() {
    abort(404);
  }

  public function abort500() {
    undefined_method();
  }
}
