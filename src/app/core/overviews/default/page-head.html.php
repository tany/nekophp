<div class="container-fluid page-head-container">
  <div class="row justify-content-between align-items-center page-head-row">
    <div class="col-8">
      <a href="/" class="h2 link-light page-head-title"><?= lc('core.name') ?></a>
    </div>

    <div class="col-4 text-end page-head-side">
      @if Core::$user
        <div class="dropdown">
          <span class="link-light user-icon" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle bi-32 display-7"><!----></i>
            <span class="visually-hidden">${Core::$user->name}</span>
          </span>

          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/@${Core::$user->name}">${Core::$user->name}</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Your Profile</a></li>
            <li><a class="dropdown-item" href="#">Your ...</a></li>
            <li><a class="dropdown-item" href="#">Your ...</a></li>
            <li><a class="dropdown-item" href="#">Your ...</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/logout">Sign out</a></li>
          </ul>
        </div>
      @else
        <a class="px-3 py-1 border rounded link-light d-inline-block" href="/login">Sign in</a>
      @end
    </div>
  </div>
</div>
