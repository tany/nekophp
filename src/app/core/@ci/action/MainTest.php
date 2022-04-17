<?php

return new class extends \ci\ActionTest {

  public function __invoke() {
    // Home
    $this->visit('/');
    $el = $this->find('.article-title');
    $this->assertSame($el->getText(), 'Home');

    // Login
    $this->visit('/login');
    $form = $this->within('.form-login');
    $form->name('data[username]')->sendKeys('user');
    $this->submit('.js-ajax-submit');

    // Logout
    $this->click('.page-header .dropdown')->link('.id-logout');
  }
};
