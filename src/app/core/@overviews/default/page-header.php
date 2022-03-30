<div class="page-header d-flex justify-content-between">
  <div>
    <a href="/" class="h2 link-light"><?= lc('--application.name') ?></a>
  </div>

  @if Core::$user
    <div class="dropdown">
      <span class="link-light user-icon" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-32 bi-person-circle display-7"><!----></i>
        <span class="visually-hidden">#{Core::$user->name}</span>
      </span>

      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="/@#{Core::$user->name}">#{Core::$user->name}</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Your Profile</a></li>
        <li><a class="dropdown-item" href="#">Your ...</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#" onclick="core.disableAjaxSubmit(this); return false">Disable ajaxSubmit</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item js-link-ajax id-logout" href="/logout">Sign out</a></li>
      </ul>
    </div>
  @else
    <a class="px-3 py-1 border rounded link-light d-inline-block" href="/login">Sign in</a>
  @end
</div>
