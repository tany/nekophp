<table class="data-table">
  <thead>
    <tr>
      <th class="p-2">Path</th>
      <th class="p-2">Action</th>
    </tr>
  </thead>
  <tbody0
    @foreach $this->items as $key => $val
    <tr>
      <td class="p-1 px-2">{$key}</td>
      <td class="p-1 px-2">{$val}</td>
    </tr>
    @end
  </tbody>
</table>
