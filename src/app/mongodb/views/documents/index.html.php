<div class="mongodb-footer">
  <?= $this->items->pager ?>
</div>

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
            <li><a class="dropdown-item text-primary js-next-link" href="?do=create">New Document</a></li>
          </ul>
        </div>
      </th>

      @foreach $this->fields as $field
      <th class="position-sticky sticky-top" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{$field}">
        <div class="text-truncate" style="width: 100px;">{$field}</div>
      </th>
      @end
    </tr>
  </thead>
  <tbody>
    @foreach $this->items as $item
    <? $id = $item->getLinkId(); ?>
    <tr class="row-link js-next-link" data-href="{$id}">
      <th class="position-sticky sticky-start row-link-disabled td-cbox">
        @if $id
        <label class="link-expand">
          <input class="form-check-input" type="checkbox" name="id" value="{$id}">
        </label>
        @end
      </th>

      @foreach $this->fields as $field
      <? $values = $item->collectValues($field); ?>
      <td class="text-nowrap type-{$values['type']}" title="{$values['long']}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="tooltip-wide">
        <div class="text-truncate" style="width: 100px;">{$values['short']}</div>
      </td>
      @end
    </tr>
    @end
  </tbody>
</table>
