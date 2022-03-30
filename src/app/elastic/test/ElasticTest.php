<?php
namespace elastic\test;

return new class extends \core\Test {

  public $title = 'Elastic Tests';
  public $index = 'test_';

  public function __invoke() {
    // before
    $this->index .= time();

    // Login
    $this->visit('/login');
    $this->name('data[username]')->sendKeys('user');
    $this->click('.js-ajax-submit');

    // Main
    $this->visit('/elastic');
    $this->click('.js-ajax-submit');

    // Index
    $this->click('.data-table thead .dropend', false);
    $this->click('.data-table thead .dropend .js-link-next');
    $this->name('data[name]')->sendKeys($this->index);
    $this->click('.js-rest-create');

    // Documents
    $this->click('.data-table thead .dropend', false);
    $this->click('.data-table thead .dropend .js-link-next');
    $this->click('.js-rest-create');

    $this->click('.page-main-footer .btn');
    $this->click('.js-rest-update');

    $this->click('.js-rest-delete');
    $this->click('.modal-footer .btn:last-child');

    $this->click('.data-table thead .dropend', false);
    $this->click('.data-table thead .dropend .js-link-next');
    $this->click('.js-rest-create');
    $this->click('.data-table thead .th-icon .js-link-next');

    // Index
    $tr = ".data-table tbody tr[data-id='{$this->index}']";
    $this->visit('/elastic/default/');
    $this->click("{$tr} .dropend", false);
    $this->click("{$tr} .dropend .js-rest-delete");
    $this->click('.modal-footer .btn:last-child');
  }
};
