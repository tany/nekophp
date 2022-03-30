<?php
namespace core\view;

use \core\error\ErrorReport;

class Engine {

  public $yield;

  protected $scope;
  protected $path;
  protected $partials = [];

  public function __construct($scope) {
    $this->scope = $scope;
  }

  public function includePath($path) {
    $this->path = $path;
    return $this;
  }

  public function setPartials($partials) {
    $this->partials = $partials;
    return $this;
  }

  public function render($file) {
    if (!$path = $this->find($file)) {
      throw new \Exception(__METHOD__ . "(): missing template: {$file}");
    }
    return $this->renderFile($path);
  }

  public function renderFrom($file, $from) {
    if (!$path = $this->findFrom($file, $from)) {
      throw new \Exception(__METHOD__ . "(): missing template: {$file}");
    }
    return $this->renderFile($path);
  }

  public function renderOverview($file) {
    $this->yield = ob_capture();
    return $this->render($file);
  }

  public function renderPartial($name, $from) {
    $file = $this->partials[$name] ?? null;
    return $file ? $this->renderFrom($file, $from) : $this;
  }

  protected function find($file) {
    if (!extname($file)) $file .= '.php';

    if ($file[0] === '/') return $file;
    if (str_contains($file, '/')) return APP . '/' . $file;
    return APP . '/' . "{$this->path}/{$file}";
  }

  protected function findFrom($file, $from) {
    if (!extname($file)) $file .= '.php';

    if ($file[0] === '/') return $file;
    if (str_contains($file, '/')) return APP . '/' . $file;
    return dirname($from) . "/{$file}";
  }

  protected function renderFile($file) {
    $view = $this;
    $closure = function($file) use ($view) {
      $request = $this->request;
      void($request, $view);
      include $file;
    };

    try {
      $closure->call($this->scope, Compiler::compile($file));
    } catch (\Throwable $t) {
      ErrorReport::setUncompiledFile($file);
      throw $t;
    }
    return $this;
  }
}
