<table class="data-table table-sticky mongodb-data-table">
  <thead>
    <tr>
      <th class="position-sticky sticky-top-start row-link-disabled td-icon">
        <a class="link-expand link-dark js-next-link" href="?"><i class="bi bi-arrow-left"><!----></i></a>
      </th>
      <th class="position-sticky sticky-top">JSON</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th class="position-sticky sticky-start row-link-disabled td-icon"></th>
      <td class="input-json">
        <textarea class="form-control text-mono" name="data[json]" id="data-json"
          spellcheck="false">${json_encode_pretty($this->item)}</textarea>
      </td>
    </tr>
  </tbody>
</table>
