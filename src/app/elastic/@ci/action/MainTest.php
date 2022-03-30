<?php

return new class extends \ci\ActionTest {

  public $index = 'test_';

  public function __construct() {
    $this->index .= time();
  }

  public function __invoke() {
    // Login
    $this->visit('/login');
    $this->name('data[username]')->sendKeys('user');
    $this->submit('.js-ajax-submit');

    // Main
    $this->visit('/elastic');
    $this->submit('.js-ajax-submit');

    // Index
    $this->click('.data-table thead .dropend')->link('.js-link-next');
    $this->name('data[name]')->sendKeys($this->index);
    $this->submit('.js-rest-create');

    // Documents
    $this->click('.data-table thead .dropend')->link('.js-link-next');
    $this->submit('.js-rest-create');

    $this->link('.page-main-footer .btn');
    $this->submit('.js-rest-update');

    $this->click('.js-rest-delete');
    $this->submit('.modal-footer .btn:last-child');

    $this->click('.data-table thead .dropend')->link('.js-link-next');
    $this->submit('.js-rest-create');

    $this->link('.data-table thead .th-icon .js-link-next');

    // Index
    $this->visit('/elastic/default/');
    $this->click(".data-table tbody tr[data-id='{$this->index}'] .dropend")
      ->click('.js-rest-delete');
    $this->submit('.modal-footer .btn:last-child');
  }
};
