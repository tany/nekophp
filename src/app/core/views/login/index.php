<article class="page-article">
  <header class="page-article-header">
    <h1 class="page-article-title">Sign in</h1>
  </header>
  <div class="page-article-body">

    <form class="form-main p-4 border bg-light" method="post" action="/login" style="max-width: 30rem;">
      <input type="hidden" name="data[path]" value="{$request->path()}">
      <div>
        <label for="data-username" class="form-label">Username</label>
        <input type="text" class="form-control" id="data-username" name="data[username]">
      </div>
      <div class="mt-3">
        <label for="data-password" class="form-label">Password</label>
        <input type="password" class="form-control" id="data-password" name="data[password]">
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-primary js-form-submit">Sign in</button>
      </div>
    </form>

  </div>
</article>
