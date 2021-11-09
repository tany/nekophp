<?php
namespace elastic\tests;

return new class extends \core\Test {

  public $title = 'Elastic Tests';
  public $index = 'test_';

  public function __invoke() {
    // before
    $this->index .= time();

    // Login
    $this->visit('/login');
    $this->name('data[username]')->sendKeys('user');
    $this->click('.js-form-submit');

    // Main
    $this->visit('/elastic');
    $this->click('.js-form-submit');

    // Index
    $this->click('.data-table thead .td-icon ', false);
    $this->click('.data-table thead .td-icon .js-next-link');
    $this->name('data[name]')->sendKeys($this->index);
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

    // Index
    $tr = ".data-table tbody tr[data-id='{$this->index}']";
    $this->visit('/elastic/default/');
    $this->click("{$tr} .td-icon:last-child", false);
    $this->click("{$tr} .td-icon:last-child .js-rest-delete");
    $this->click('.modal-footer .btn:last-child');

    // $this->screenshot();
  }
};
