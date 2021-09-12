<?php
namespace core\tests;

return new class extends \core\Test {

  public function __invoke() {
    $this->visit('/');
    $el = $this->find('.page-article-title');
    $this->assertSame($el->getText(), 'Home');

    // Login
    $this->visit('/login');
    $this->name('data[username]')->sendKeys('user');
    $this->click('.js-form-submit');

    // Logout
    $this->click('.page-header .dropdown', false);
    $this->click('.page-header .dropdown-menu .js-logout');
  }
};
