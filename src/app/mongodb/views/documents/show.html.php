<table class="data-table table-sticky mongodb-data-table">
  <thead>
    <tr>
      <th class="position-sticky sticky-top-start row-link-disabled td-icon">
        <a class="link-expand link-dark js-next-link" href="."><i class="bi bi-arrow-left"><!----></i></a>
      </th>
      <th class="position-sticky sticky-top" style="width: 16rem;">Field</th>
      <th class="position-sticky sticky-top d-none d-md-table-cell" style="width: 16rem;">Type</th>
      <th class="position-sticky sticky-top" style="width: 42rem;">Value</th>
    </tr>
  </thead>
  <tbody>
    @foreach $this->fields as $idx => $field
    <?
      $values = $this->item->collectValues($field);
      $rows = substr_count($values['full'], "\n") + preg_match_all('/[^\n]{100,}/', $values['full']);
      $height = (2.2 + $rows * 1.5) . 'rem';
    ?>
    <tr>
      <th class="position-sticky sticky-start row-link-disabled td-icon" data-num="{$idx + 1}"></th>
      <td class="text-break">{$field}</td>
      <td class="text-break text-start type-{$values['type']} d-none d-md-table-cell">{$values['type']}</td>
      <td class="p-0">
        <textarea class="textarea-transparent" readonly="true" style="height: {$height};">{$values['full']}</textarea>
      </td>
    </tr>
    @end
  </tbody>
</table>

<div class="mongodb-footer">
  <a type="button" class="btn btn-secondary js-next-link" href="?do=update">Edit Document</a>
  <button type="button" class="btn btn-danger js-rest-delete ml-4"
    data-confirm="${lc('core.confirm.delete')}">Delete Document</button>
</div>
