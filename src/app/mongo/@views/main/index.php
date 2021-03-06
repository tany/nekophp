<article class="article">
  <header class="article-header">
    <h1 class="article-title">MongoDB</h1>
  </header>
  <div class="article-body">

    <form class="sub-form form-connection" method="post" action="" style="max-width: 30rem;">
      <label for="data-name" class="form-label">Connection</label>
      <select class="form-select" id="data-name" name="data[name]" aria-label="client name">
        @foreach $this->clients as $key => $val
        <option value="{$key}">{$val['name']}</option>
        @end
      </select>
      <div class="mt-md">
        <button type="submit" class="btn btn-primary js-ajax-submit">Connect</button>
      </div>
    </form>

  </div>
</article>
