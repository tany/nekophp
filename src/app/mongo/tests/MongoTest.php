<?php
namespace mongo\tests;

return new class extends \core\Test {

  public $title = 'MongoDB Tests';
  public $database = '_test_';
  public $collection = 'coll';

  public function __invoke() {
    // before
    $this->database .= time();

    // Login
    $this->visit('/login');
    $this->name('data[username]')->sendKeys('user');
    $this->click('.js-form-submit');

    // Main
    $this->visit('/mongo');
    $this->click('.js-form-submit');

    // Databases
    $this->click('.data-table thead .td-icon ', false);
    $this->click('.data-table thead .td-icon .js-next-link');
    $this->name('data[name]')->sendKeys($this->database);
    $this->click('.js-rest-create');

    // Collections
    $this->click('.data-table thead .td-icon', false);
    $this->click('.data-table thead .td-icon .js-next-link');
    $this->name('data[name]')->sendKeys($this->collection);
    $this->click('.js-rest-create');

    // Documents
    $this->click('.data-table thead .td-icon', false);
    $this->click('.data-table thead .td-icon .js-next-link');
    $this->click('.js-rest-create');

    $this->click('.page-main-footer .btn:first-child');
    $this->click('.js-rest-update');

    $this->click('.js-rest-delete');
    $this->click('.modal-footer .btn:last-child');

    $this->click('.data-table thead .td-icon', false);
    $this->click('.data-table thead .td-icon .js-next-link');
    $this->click('.js-rest-create');
    $this->click('.data-table thead .td-icon .js-next-link');

    // Database
    $tr = ".data-table tbody tr[data-id='{$this->database}']";
    $this->visit('/mongo/default/');
    $this->click("{$tr} .td-icon:last-child", false);
    $this->click("{$tr} .td-icon:last-child .js-rest-delete");
    $this->click('.modal-footer .btn:last-child');

    // $this->screenshot();
  }
};
