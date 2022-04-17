<article class="article">
  <header class="article-header">
    <h1 class="article-title">Sign in</h1>
  </header>
  <div class="article-body">
    <form class="sub-form form-login" method="post" action="/login" style="max-width: 30rem;">
      <input type="hidden" name="data[redirect]" value="{$this->redirect}">
      <div>
        <label for="data-username" class="form-label">Username</label>
        <input type="text" class="form-control" id="data-username" name="data[username]" value="testuser">
      </div>
      <div class="mt-2">
        <label for="data-password" class="form-label">Password</label>
        <input type="password" class="form-control" id="data-password" name="data[password]">
      </div>
      <div class="mt-md">
        <button type="submit" class="btn btn-primary js-ajax-submit">Sign in</button>
      </div>
    </form>

  </div>
</article>
