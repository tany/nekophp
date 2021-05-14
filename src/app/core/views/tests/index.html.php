<h1 class="h3">Test</h1>

<script>
  window.onload = function() {
    $('.jquery-test').html(moment().format('Y/M/D HH:mm:ss'))
  }
</script>

<table class="data-table">
  <thead>
    <tr>
      <th>Target</th>
      <th>Result</th>
    </tr>
  </thead>
  <tbody>
    @foreach $this->items as $item
    <tr>
      <td>@foreach</td>
      <td>{$item->name}</td>
    </tr>
    @end
    <tr>
      <td>$ { func() }</td>
      <td>${lc('core.alert.created')}</td>
    </tr>
    <tr>
      <td>Icon</td>
      <td>
        <i class="fas fa-crow"><!----></i>
        / <i class="bi bi-speedometer"><!----></i>
      </td>
    </tr>
    <tr>
      <td>jQuery</td>
      <td class="jquery-test">-</td>
    </tr>
    <tr>
      <td>Response</td>
      <td>
        <a href="?do=sendData">Data</a>
        / <a href="?do=sendJson">JSON</a>
        / <a href="?do=sendLocation">Redirect</a>
      </td>
    </tr>
    <tr>
      <td>Downlaod</td>
      <td>
        <a href="?do=downloadData">Data</a>
        / <a href="?do=downloadJson">JSON</a>
        / <a href="?do=downloadFile">File</a>
      </td>
    </tr>
    <tr>
      <td>Abort</td>
      <td>
        <a href="?do=abort403">403</a>
        / <a href="?do=abort404">404</a>
      </td>
    </tr>
    <tr>
      <td>Unexpected Action</td>
      <td>
        <a href="?do=__invoke">Uncollable</a>
        / <a href="?do=before">Protected</a>
        / <a href="?do=abort500">Exception</a>
      </td>
    </tr>
    <tr>
      <td>Undefined Action</td>
      <td>
        <a href="/undefined">Undefined Route</a>
        / <a href="?do=undefined">Undefined Method</a>
        / <a href="?do=undefined&_routes=1">Show Routes</a>
      </td>
    </tr>
  </tbody>
</table>

<nav class="mt-4">
  <?= paginate(range(0, 10010)) ?>
</nav>
