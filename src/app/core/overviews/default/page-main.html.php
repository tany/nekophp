<div class="container-fluid page-main-container">
  <div class="main-content" id="content">

    @if $alert = cookie_flash('alert__*')
    <div class="main-alert">
      @foreach $alert as $key => $val
      <div class="alert alert-{$key} alert-dismissible fade show" role="alert">
        {$val}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @end
    </div>
    @end

    @yield
  </div>
</div>
