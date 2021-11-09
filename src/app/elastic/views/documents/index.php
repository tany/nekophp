<div class="page-main-footer position-fixed d-flex-center">
  <?= $this->items->pager ?>
</div>

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
          <li><a class="dropdown-item link-primary js-next-link" href="?_create">New Document</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item link-danger js-list-delete">Delete</a></li>
        </ul>
      </th>
      <th data-bs-toggle="tooltip" data-bs-placement="bottom" title="_id">
        <div class="text-truncate" style="width: 100px;">_id</div>
      </th>

      @foreach $this->fields ?: ['No Date'] as $name
      <th data-bs-toggle="tooltip" data-bs-placement="bottom" title="{$name}">
        <div class="text-truncate" style="width: 100px;">{$name}</div>
      </th>
      @end
    </tr>
  </thead>
  <tbody>
    @foreach $this->items as $item
    <tr class="js-row-link" data-id="{$item->_id}" data-next-href="#{rawurlencode($item->_id)}">
      <th class="js-row-unlink td-check">
        <label class="link-expand">
          <input class="form-check-input" type="checkbox" name="id" value="{$item->_id}">
        </label>
      </th>

      <? $values = $item->values('_id'); ?>
      <td class="text-nowrap type-{$values->type}" title="{$values->tips}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="tooltip-wide">
        <div class="text-truncate" style="width: 100px;">{$values->cell}</div>
      </td>

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

