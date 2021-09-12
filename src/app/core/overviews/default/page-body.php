<div class="page-body container-fluid">
  <div class="page-menu"></div>

  <div class="page-main" id="main">
    @if $alert = cookie_flash('alert__*')
    <div class="page-alert">
      @foreach $alert as $key => $val
      <div class="alert alert-{$key} alert-dismissible fade show" role="alert">
        {$val}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @end
    </div>
    @end

    @yield
  </div>

  <div class="page-side"></div>
</div>
