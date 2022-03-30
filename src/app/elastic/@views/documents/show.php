<table class="data-table table-sticky table-db">
  <thead>
    <tr>
      <th class="th-icon unlink">
        <a class="link-expand link-dark js-link-next" href=".">
          <i class="bi bi-md bi-arrow-left"><!----></i></a>
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
      <th class="th-icon unlink"></th>
      <td class="text-break fw-bold">{$name}</td>
      <td class="text-break text-muted d-none d-xl-table-cell">#{gettype($data)}</td>
      <td class="text-break type-#{gettype($data)} text-start">
        <a href="#" class="float-end link-muted js-clipboard-copy">
          <i class="bi bi-md bi-clipboard"><!----></i></a>
        <pre class="pre-value">{$data}</pre>
      </td>
    </tr>
    @end

    @foreach $this->fields as $idx => $name
    <? $values = $this->item->values($name); ?>
    <tr>
      <th class="th-icon unlink"></th>
      <td class="text-break">{$name}</td>
      <td class="text-break text-muted d-none d-xl-table-cell">{$values->type}</td>
      <td class="text-break type-{$values->type} text-start">
        <a href="#" class="float-end link-muted js-clipboard-copy">
          <i class="bi bi-md bi-clipboard"><!----></i></a>
        <pre class="pre-value">{$values->full}</pre>
      </td>
    </tr>
    @end
  </tbody>
</table>

<div class="page-main-footer position-fixed d-flex-center">
  <a class="btn btn-secondary js-link-next" href="?_update">Edit Document</a>
  <button type="button" class="btn btn-danger js-rest-delete"
    data-confirm="#{lc('--confirm.delete')}">Delete Document</button>
</div>
