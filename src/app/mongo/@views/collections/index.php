<table class="data-table table-sticky table-db">
  <thead>
    <tr>
      <th class="th-icon sticky-start">
        <label class="link-expand">
          <input class="form-check-input js-list-select" type="checkbox">
        </label>
      </th>
      <th class="th-icon sticky-start dropend">
        <a class="link-expand link-muted" role="button"
          data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="1,2">
          <i class="bi bi-md bi-list"><!----></i></a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item link-primary js-link-next" href="?_create">New Collection</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-list-delete">Delete</a></li>
        </ul>
      </th>
      <th style="min-width: 20rem;">Collection</th>
      <th style="min-width: 5rem;">Docs</th>
      <th style="min-width: 5rem;">Size</th>
    </tr>
  </thead>
  <tbody>
    @foreach $this->items as $idx => $item
    <? $id = $item->name ?>
    <tr class="js-link-row" data-id="{$id}" data-href="#{rawurlencode($id)}/">
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
          <li><a class="dropdown-item link-primary js-link-next" href="#{rawurlencode($id)}/"
            target="_blank">Open in new tab</a></li>
          <li><a class="dropdown-item link-primary js-link-next" href="#{rawurlencode($id)}?_update">Rename</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-rest-delete" href="#{rawurlencode($id)}"
            data-confirm="#{lc('--confirm.delete')}">Delete</a></li>
        </ul>
      </th>
      <td class="text-nowrap text-truncate">{$item->name}</td>
      <td class="text-end">#{number_format($item->dataSize->numObjects)}</td>
      <td class="text-end">#{size_kb($item->dataSize->size)}</td>
    </tr>
    @end
  </tbody>
</table>
