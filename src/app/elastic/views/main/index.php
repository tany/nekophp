<article class="page-article">
  <header class="page-article-header">
    <h1 class="page-article-title">Elasticsearch</h1>
  </header>
  <div class="page-article-body">

    <form class="form-main p-4 border bg-light" method="post" action="" style="max-width: 30rem;">
      <label for="data-name" class="form-label">Connection</label>
      <select class="form-select" id="data-name" name="data[name]" aria-label="client name">
        @foreach $this->clients as $key => $val
        <option value="{$key}">{$val['name']}</option>
        @end
      </select>
      <div class="mt-4">
        <button type="submit" class="btn btn-primary js-form-submit">Connect</button>
      </div>
    </form>

  </div>
</article>
