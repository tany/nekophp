<table class="data-table table-sticky table-db">
  <thead>
    <tr>
      <th class="th-icon sticky-start">
        <label class="link-expand">
          <input class="form-check-input js-list-select" type="checkbox">
        </label>
      </th>
      <th class="th-icon sticky-start dropend">
        <a class="link-expand link-dark" role="button"
          data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="1,2">
          <i class="bi bi-md bi-list"><!----></i></a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item link-primary js-link-next" href="?_create">New Index</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-list-delete">Delete</a></li>
        </ul>
      </th>
      <th style="min-width: 20rem;">Index</th>
      <th style="min-width: 5rem;">Health</th>
      <th style="min-width: 5rem;">Status</th>
      <th style="min-width: 5rem;">Docs</th>
      <th style="min-width: 5rem;">Size</th>
    </tr>
  </thead>
  <tbody>
    @foreach $this->items as $idx => $item
    <? $id = $item->name ?>
    <tr class="js-link-row js-link-next" data-id="{$id}" data-href="#{rawurlencode($id)}/">
      <th class="th-icon sticky-start unlink">
        <label class="link-expand">
          <input class="form-check-input" type="checkbox" name="id" value="{$id}">
        </label>
      </th>
      <th class="th-icon sticky-start dropend unlink">
        <a class="link-expand link-muted" role="button"
          data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="1,2">
          <i class="bi bi-md bi-three-dots-vertical"><!----></i></a>

        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item link-muted dropdown-switch" href="">More menu ...</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-primary js-link-next" href="{$id}?_update">Reindex</a></li>
          <li><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_open">Open</a></li>
          <li><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_close">Close</a></li>
          <li><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_mapping">Mapping</a></li>
          <li><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_settings">Settings</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-rest-delete" href="{$id}"
            data-confirm="#{lc('--confirm.delete')}">Delete</a></li>

          <li class="d-none"><a class="dropdown-item link-muted dropdown-switch" href="#">Standard menu ...</a></li>
          <li class="d-none"><hr class="dropdown-divider"></li>
          <li class="d-none"><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_stats">Stats</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_segments">Segments</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_recovery">Recovery</a></li>
          <li class="d-none"><hr class="dropdown-divider"></li>
          <li class="d-none"><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_clearCache">Clear cache</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_refresh">Refresh</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_flush">Flush</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_flushSynced">Synced flush</a></li>
          <!--
          <li class="d-none"><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_getAlias">Get alias</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-link-ajax" href="{$id}?_getUpgrade">Get upgrade</a></li>
          -->
        </ul>
      </th>
      <td class="text-nowrap text-truncate">{$item->name}</td>
      <td class="text-center">{$item->health}</td>
      <td class="text-center">{$item->status}</td>
      <td class="text-end">{$item->docs}</td>
      <td class="text-end">{$item->size}</td>
    </tr>
    @end
  </tbody>
</table>
