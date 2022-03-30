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
          <li><a class="dropdown-item link-primary js-link-next" href="?_create">New Document</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-list-delete">Delete</a></li>
        </ul>
      </th>

      @foreach $this->fields ?: ['No Data'] as $name
      <th data-bs-toggle="tooltip" data-bs-placement="bottom" title="{$name}">
        <div class="text-truncate" style="width: 100px;">{$name}</div>
      </th>
      @end
    </tr>
  </thead>
  <tbody>
    @foreach $this->items as $item
    <? $id = $item->hrefId() ?>
    <tr class="js-link-row js-link-next" data-id="{$id}" data-href="#{rawurlencode($id)}">
      <th class="th-icon sticky-start unlink">
        @if $id
        <label class="link-expand">
          <input class="form-check-input" type="checkbox" name="id" value="{$id}">
        </label>
        @end
      </th>
      <th class="th-icon sticky-start dropend unlink">
        <a class="link-expand link-muted" role="button"
          data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="1,2">
          <i class="bi bi-md bi-three-dots-vertical"><!----></i></a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item link-primary js-link-next" href="#{rawurlencode($id)}"
            target="_blank">Open in new tab</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-rest-delete" href="#{rawurlencode($id)}"
            data-confirm="#{lc('--confirm.delete')}">Delete</a></li>
        </ul>
      </th>

      @foreach $this->fields as $name
      <? $values = $item->values($name); ?>
      <td class="text-nowrap type-{$values->type}" title="{$values->tips}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="tooltip-wide">
        <div class="text-truncate" style="width: 100px;">{$values->cell}</div>
      </td>
      @end
    </tr>
    @end
  </tbody>
</table>

<div class="page-main-footer position-fixed d-flex-center">
  <?= $this->items->pager ?>
</div>
