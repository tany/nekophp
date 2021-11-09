<table class="data-table table-db">
  <thead>
    <tr>
      <th class="td-icon dropend">
        <a class="link-expand link-dark"
          role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-md bi-list"><!----></i></a>

        <ul class="dropdown-menu">
          <li><a class="dropdown-item link-dark js-list-select">Select All</a></li>
          <li><a class="dropdown-item link-dark js-list-deselect">Deselect All</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-primary js-next-link" href="?_create">New Index</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-list-delete">Delete</a></li>
        </ul>
      </th>
      <th style="min-width: 20rem;">Index</th>
      <th style="min-width: 5rem;">Health</th>
      <th style="min-width: 5rem;">Status</th>
      <th style="min-width: 5rem;">Docs</th>
      <th style="min-width: 5rem;">Size</th>
      <th class="td-icon"></th>
    </tr>
  </thead>
  <tbody>
    @foreach $this->items as $idx => $item
    <tr class="js-row-link" data-id="{$item->name}">
      <th class="js-row-unlink td-check">
        <label class="link-expand">
          <input class="form-check-input" type="checkbox" name="id" value="{$item->name}">
        </label>
      </th>
      <td class="text-nowrap text-truncate">
        <a href="#{rawurlencode($item->name)}/" class="link-inherit">{$item->name}</a>
      </td>
      <td class="text-center">{$item->health}</td>
      <td class="text-center">{$item->status}</td>
      <td class="text-end">{$item->docs}</td>
      <td class="text-end">{$item->size}</td>
      <td class="js-row-unlink td-icon dropend">
        <a class="link-expand link-muted" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-md bi-three-dots-vertical"><!----></i></a>

        <ul class="dropdown-menu">
          <li><a class="dropdown-item link-primary js-next-link" href="{$item->name}?_update">Reindex</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-rest-delete" href="{$item->name}"
            data-confirm="#{lc('core.confirm.delete')}">Delete</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_open">Open</a></li>
          <li><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_close">Close</a></li>
          <li><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_mapping">Mapping</a></li>
          <li><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_settings">Settings</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-muted dropdown-switch">More</a></li>

          <li class="d-none"><a class="dropdown-item link-muted dropdown-switch"><i class="bi bi-arrow-left"><!----></i></a></li>
          <li class="d-none"><hr class="dropdown-divider"></li>
          <li class="d-none"><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_stats">Stats</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_segments">Segments</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_recovery">Recovery</a></li>
          <li class="d-none"><hr class="dropdown-divider"></li>
          <li class="d-none"><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_clearCache">Clear cache</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_refresh">Refresh</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_flush">Flush</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_flushSynced">Synced flush</a></li>
          <!--
          <li class="d-none"><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_getAlias">Get alias</a></li>
          <li class="d-none"><a class="dropdown-item link-primary js-ajax-link" href="{$item->name}?_getUpgrade">Get upgrade</a></li>
          -->
        </ul>
      </td>
    </tr>
    @end
  </tbody>
</table>
