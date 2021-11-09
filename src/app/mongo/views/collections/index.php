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
          <li><a class="dropdown-item link-primary js-next-link" href="?_create">New Collection</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-list-delete">Delete</a></li>
        </ul>
      </th>
      <th style="min-width: 20rem;">Collection</th>
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
      <td class="text-end">#{number_format($item->dataSize->numObjects)}</td>
      <td class="text-end">#{size_kb($item->dataSize->size)}</td>
      <td class="js-row-unlink td-icon dropend">
        <a class="link-expand link-muted"
          role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-md bi-three-dots-vertical"><!----></i></a>

        <ul class="dropdown-menu">
          <li><a class="dropdown-item link-primary js-next-link" href="{$item->name}?_update">Rename</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-rest-delete" href="{$item->name}"
            data-confirm="#{lc('core.confirm.delete')}">Delete</a></li>
        </ul>
      </td>
    </tr>
    @end
  </tbody>
</table>
