<table class="data-table table-db">
  <thead>
    <tr>
      <th class="row-link-disabled td-icon">
        <a class="link-expand link-dark js-next-link" href="."><i class="bi bi-md bi-arrow-left"><!----></i></a>
      </th>
      <th style="width: 16rem;">Field</th>
      <th class="d-none d-xl-table-cell" style="width: 16rem;">Type</th>
      <th style="width: 42rem;">Value</th>
    </tr>
  </thead>
  <tbody>
    @foreach ['_index', '_type', '_id'] as $name
    <? $data = $this->item->$name; ?>
    <tr>
      <th class="row-link-disabled td-icon"></th>
      <td class="text-break">{$name}</td>
      <td class="text-break text-muted d-none d-xl-table-cell">#{gettype($data)}</td>
      <td class="text-break type-#{gettype($data)} text-start">
        <a href="#" class="float-end link-dark js-clipboard-copy"><i class="bi bi-md bi-clipboard"></i></a>
        <pre class="pre-value">{$data}</pre>
      </td>
    </tr>
    @end

    <tr><th colspan="4"></th></tr>

    @foreach $this->fields as $idx => $name
    <? $field = $this->item->fieldManager($name); ?>
    <tr>
      <th class="row-link-disabled td-icon"></th>
      <td class="text-break">{$name}</td>
      <td class="text-break text-muted d-none d-xl-table-cell">{$field->type}</td>
      <td class="text-break type-{$field->type} text-start">
        <a href="#" class="float-end link-dark js-clipboard-copy"><i class="bi bi-md bi-clipboard"></i></a>
        <pre class="pre-value">{$field->full}</pre>
      </td>
    </tr>
    @end
  </tbody>
</table>

<div class="page-main-footer position-fixed d-flex-center">
  <a type="button" class="btn btn-secondary js-next-link" href="?_update">Edit Document</a>
  <button type="button" class="btn btn-danger js-rest-delete"
    data-confirm="#{lc('core.confirm.delete')}">Delete Document</button>
</div>
