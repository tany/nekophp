<?php
namespace core\view;

use \core\error\ErrorReport;

class Engine {

  public $code;
  public $yield;
  protected $scope;
  protected $views;

  public function __construct($scope) {
    $this->scope = $scope;
  }

  public function includePath($paths) {
    $this->views = $paths;
    return $this;
  }

  public function setPartials($paths) {
    $this->partials = $paths;
    return $this;
  }

  public function render($file) {
    if (!$path = $this->find($file)) {
      throw new \Exception(__METHOD__ . "(): missing template: {$file}");
    }
    return $this->renderFile($path);
  }

  public function renderFrom($file, $from) {
    return $this->render($file);
  }

  public function renderOverview($file) {
    $this->yield = ob_capture();
    return $this->render($file);
  }

  public function renderPartial($file) {
    $path = $this->partials[$file] ?? null;
    if ($path) return $this->render($path);
  }

  protected function find($file) {
    if (!extname($file)) $file .= ".html.php";
    if ($file[0] === '/') return APP . $file;

    foreach ($this->views as $dir) {
      $path = stream_resolve_include_path("{$dir}/{$file}");
      if ($path) return $path;
    }
    return;
  }

  protected function renderFile($file) {
    $closure = function($file) {
      $request = $this->request;
      $view = $this->view;
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
