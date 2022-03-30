<?php

return new class extends \ci\ActionTest {

  public $database = '_test_';
  public $collection = 'coll';

  public function __construct() {
    $this->database .= time();
  }

  public function __invoke() {
    // Login
    $this->visit('/login');
    $this->name('data[username]')->sendKeys('user');
    $this->submit('.js-ajax-submit');

    // Main
    $this->visit('/mongo');
    $this->submit('.js-ajax-submit');

    // Databases
    $this->click('.data-table thead .dropend')->link('.js-link-next');
    $this->name('data[name]')->sendKeys($this->database);
    $this->submit('.js-rest-create');

    // Collections
    $this->click('.data-table thead .dropend')->link('.js-link-next');
    $this->name('data[name]')->sendKeys($this->collection);
    $this->submit('.js-rest-create');

    // Documents
    $this->click('.data-table thead .dropend')->link('.js-link-next');
    $this->submit('.js-rest-create');

    $this->link('.page-main-footer .btn:first-child');
    $this->submit('.js-rest-update');

    $this->click('.js-rest-delete');
    $this->submit('.modal-footer .btn:last-child');

    $this->click('.data-table thead .dropend')->link('.js-link-next');
    $this->submit('.js-rest-create');

    $this->link('.data-table thead .th-icon .js-link-next');

    // Database
    $this->visit('/mongo/default/');
    $this->click(".data-table tbody tr[data-id='{$this->database}'] .dropend")
      ->click('.js-rest-delete');
    $this->submit('.modal-footer .btn:last-child');
  }
};
