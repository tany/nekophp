<table class="data-table table-sticky table-db">
  <thead>
    <tr>
      <th class="th-icon unlink">
        <a class="link-expand link-dark js-link-next" href=".">
          <i class="bi bi-md bi-arrow-left"><!----></i></a>
      </th>
      <th style="width: 16rem;">Field</th>
      <th class="d-none d-xl-table-cell" style="width: 16rem;">Type</th>
      <th style="width: 44rem;">Value</th>
    </tr>
  </thead>
  <tbody>
    @foreach $this->fields as $name
    <? $val = $this->item->dbValue($name); ?>
    <tr>
      <th class="th-icon unlink"></th>
      <td class="text-break">{$name}</td>
      <td class="text-break text-muted d-none d-xl-table-cell">{$val->type}</td>
      <td class="text-break text-start db-value db-type-{$val->type}">
        <a href="#" class="float-end link-muted js-clipboard-copy">
          <i class="bi bi-md bi-clipboard"><!----></i></a>
        <pre class="pre-value">{$val->long}</pre>
      </td>
    </tr>
    @end
  </tbody>
</table>

<div class="main-footer footer-fixed">
  <a class="btn btn-secondary js-link-next" href="?_update">Edit Document</a>
  <button type="button" class="btn btn-danger js-rest-delete"
    data-confirm="{\lc('--confirm.delete')}">Delete Document</button>
</div>
