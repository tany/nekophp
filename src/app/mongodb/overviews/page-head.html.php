<script>
  window.onload = function() {
    $('textarea').on('click', function() {
      $('.dropdown-menu').removeClass('show')
    })

    var q = $('#mongodb-query').val()
    if (q) $('#mongodb-query').focus().get(0).setSelectionRange(q.length, q.length)
  }
</script>

<nav class="position-fixed navbar navbar-expand-md align-items-start mongodb-header">
    <div class="navbar-nav">
      <div class="dropdown">
        <span class="btn btn-secondary dropdown-toggle"
          role="button" data-bs-toggle="dropdown" aria-expanded="false">
          {$this->conn->hostname}</span>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="/mongodb">Disconnect</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/">Back to Home</a></li>
        </ul>
      </div>
    </div>

    <span class="navbar-toggler link-dark" data-bs-toggle="collapse" data-bs-target="#page-navi-navbar"
      role="button" aria-controls="page-navi-navbar" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list bi-24"><!----></i>
    </span>

    <div class="collapse navbar-collapse" id="page-navi-navbar">
      <div class="navbar-nav">
        <div class="dropdown">
          <span class="btn btn-secondary dropdown-toggle"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {$request->db ?? 'Select Database'}</span>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/mongodb/db/">Select Database</a></li>
            <li><hr class="dropdown-divider"></li>
            @foreach $this->conn->databases() as $item
            <?php $class = ($item->name === $request->db) ? 'active'  : ''; ?>
            <li><a class="dropdown-item {$class}" href="/mongodb/db/{$item->name}/">{$item->name}</a></li>
            @end
          </ul>
        </div>

        @if $request->db
        <div class="dropdown">
          <span class="btn btn-secondary dropdown-toggle"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {$request->coll ?? 'Select Collection'}</span>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/mongodb/db/{$request->db}/">Select Collection</a></li>
            <li><hr class="dropdown-divider"></li>
            @foreach $this->conn->collections($request->db) as $item
            <?php $class = ($item->name === $request->coll) ? 'active'  : ''; ?>
            <li><a class="dropdown-item {$class}" href="/mongodb/db/{$request->db}/{$item->name}/">
              {$item->name}</a></li>
            @end
          </ul>
        </div>
        @end

        @if $request->coll
        <form class="search" method="get" action="./">
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="mongodb-query" placeholder="field: value" aria-label="Search"
              name="q" value="{$request->q}" style="width: 20rem;">
            <button type="submit" class="input-group-text">
              <i class="bi bi-search bi-15"><!----></i></button>
          </div>
        </form>
        @end
      </div>
    </div>
</nav>
