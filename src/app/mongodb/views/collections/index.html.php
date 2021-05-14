<table class="data-table table-sticky mongodb-data-table">
  <thead>
    <tr>
      <th class="position-sticky sticky-top-start td-icon">
        <div class="dropend">
          <a class="link-expand link-dark"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-list bi-15"><!----></i></a>

          <ul class="dropdown-menu">
            <li><a class="dropdown-item link-dark js-list-select">Select All</a></li>
            <li><a class="dropdown-item link-dark js-list-deselect">Deselect All</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item link-danger text-danger js-list-delete">Delete</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-primary js-next-link" href="?do=create">New Collection</a></li>
          </ul>
        </div>
      </th>
      <th class="position-sticky sticky-top" style="min-width: 20rem;">Collection</th>
      <th class="position-sticky sticky-top" style="min-width: 5rem;">Docs</th>
      <th class="position-sticky sticky-top" style="min-width: 5rem;">Size</th>
      <th class="position-sticky sticky-top td-icon"></th>
    </tr>
  </thead>
  <tbody>
    @foreach $this->collections as $idx => $data
    <tr class="row-link">
      <th class="position-sticky sticky-start row-link-disabled td-cbox">
        <label class="link-expand">
          <input class="form-check-input" type="checkbox" name="id" value="{$data->name}">
        </label>
      </th>
      <td class="text-nowrap text-truncate"><a href="{$data->name}/">{$data->name}</a></td>
      <td class="text-end">${number_format($data->dataSize->numObjects)}</td>
      <td class="text-end">${size_kb($data->dataSize->size)}</td>
      <td class="row-link-disabled td-icon">
        <div class="dropend">
          <a class="link-expand link-muted"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-three-dots-vertical bi-16"><!----></i></a>

          <ul class="dropdown-menu">
            <li><a class="dropdown-item text-primary js-next-link" href="{$data->name}?do=update">Rename</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item link-danger text-danger js-rest-delete" href="{$data->name}"
              data-confirm="${lc('core.confirm.delete')}">Delete</a></li>
          </ul>
        </div>
      </td>
    </tr>
    @end
  </tbody>
</table>
