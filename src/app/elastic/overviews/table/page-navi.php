<script>
  window.onload = function() {
    $('textarea').on('click', function() {
      $('.dropdown-menu').removeClass('show')
    })

    var q = $('#db-query').val()
    if (q) $('#db-query').focus()[0].setSelectionRange(q.length, q.length)
  }
</script>

<nav class="core-db-navi position-fixed d-flex">
  <div class="dropdown">
    <span class="btn btn-sm btn-secondary dropdown-toggle"
      role="button" data-bs-toggle="dropdown" aria-expanded="false">
      {$this->conn->name}</span>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="/elastic">Disconnect</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="/">Home</a></li>
    </ul>
  </div>

  <div class="dropdown">
    <span class="btn btn-sm btn-secondary dropdown-toggle"
      role="button" data-bs-toggle="dropdown" aria-expanded="false">
      {$request->index ?? 'Select Index'}</span>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="/elastic/index/">Select Index</a></li>
      <li><hr class="dropdown-divider"></li>
      @foreach $this->conn->indices() as $item
        <?php $class = ($item->name === $request->index) ? 'active'  : ''; ?>
        <li><a class="dropdown-item {$class}" href="/elastic/index/{$item->name}/">{$item->name}</a></li>
      @end
    </ul>
  </div>

  @if $request->index && str_ends_with(get_class($this), 'Documents')
    <form class="form-search" method="get" action="./">
      <div class="input-group input-group-sm">
        <input type="text" class="form-control" id="db-query" placeholder="field: value" aria-label="Search"
          name="q" value="{$request->q}" style="width: 16rem;">
        <button type="submit" class="input-group-text">
          <i class="bi bi-md bi-search"><!----></i></button>
        <button type="button" class="input-group-text" onclick="location.href = '.'">
          <i class="bi bi-md bi-x"><!----></i></button>
      </div>
    </form>
  @end
</nav>
