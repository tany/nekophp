<?php
namespace ci;

class NestedNode {

  protected $parent;

  public function __construct($parent, $selector) {
    $this->parent = $parent;
    $this->selector = $selector;
  }

  public function get($code) {
    return $this->parent->get($code);
  }

  public function find($selector) {
    return $this->parent->find($selector);
  }

  public function name($selector) {
    return $this->parent->name($selector);
  }

  public function click($selector) {
    return $this->parent->click("{$this->selector} {$selector}");
  }

  public function link($selector) {
    return $this->parent->link("{$this->selector} {$selector}");
  }

  public function submit($selector) {
    return $this->parent->submit("{$this->selector} {$selector}");
  }
}
