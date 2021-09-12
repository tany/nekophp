<?php
namespace core\actions;

use \core\feature\AccessorClass;

class Examples extends \core\Action {

  use \core\actions\LoginFilter;

  public function index($request) {
    $item = new AccessorClass(['name' => '<s>変数</s>']);
    $this->items = [$item];
    $this->response->render();
  }

  public function sendData() {
    $data = 'Test Data';
    $this->response->body($data)->type('text/plain');
  }

  public function sendJson() {
    $data = ['message' => 'Test Data'];
    $this->response->json($data);
  }

  public function downloadData() {
    $data = file_read(ROOT . '/README.md');
    $this->response->body($data, 'README_DATA.md');
  }

  public function downloadJson() {
    $data = ['message' => 'Test Data'];
    $this->response->json($data, 'README.json');
  }

  public function downloadFile() {
    $file = ROOT . '/README.md';
    $this->response->file($file, 'README_FILE.md');
  }

  public function sendLocation() {
    $this->response->location(null);
  }

  public function alert1() {
    $this->alert('Message', 'primary')->location(null);
  }

  public function alert2() {
    $this->alert('Message', 'danger')->location(null);
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
