<div class="page-main-footer position-fixed d-flex-center">
  <?= $this->items->pager ?>
</div>

<table class="data-table table-db">
  <thead>
    <tr>
      <th class="td-icon">
        <div class="dropend">
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
        </div>
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
    <tr class="row-link js-next-link" data-href="#{rawurlencode($item->_id)}">
      <th class="row-link-disabled td-check">
        <label class="link-expand">
          <input class="form-check-input" type="checkbox" name="id" value="{$item->_id}">
        </label>
      </th>

      <? $field = $item->fieldManager('_id'); ?>
      <td class="text-nowrap type-{$field->type}" title="{$field->long}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="tooltip-wide">
        <div class="text-truncate" style="width: 100px;">{$field->snip}</div>
      </td>

      @foreach $this->fields as $name
      <? $field = $item->fieldManager($name); ?>
      <td class="text-nowrap type-{$field->type}" title="{$field->long}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="tooltip-wide">
        <div class="text-truncate" style="width: 100px;">{$field->snip}</div>
      </td>
      @end
    </tr>
    @end
  </tbody>
</table>

