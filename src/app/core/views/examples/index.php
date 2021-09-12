<script>
  window.onload = function() {
    //
  }
</script>

<article class="page-article">
  <header class="page-article-header">
    <h1 class="page-article-title">Examples</h1>
  </header>
  <div class="page-article-body">

    <div class="table-responsive">
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

          <tr><td>#&#123;func()}</td><td>
            #{lc('core.alert.created')}

          </td></tr><tr><td>Javascript</td><td>
            <a href="#" onclick="Core.modal.alert({ message: moment() }); return false">moment</a>
            / <a href="#" onclick="Core.modal.alert({ title: 'Title', message: '<s>Message\nMessage</s>' }); return false">Alert</a>
            / <a href="#" onclick="Core.modal.confirm({ message: 'Message Only' }); return false">Confirm</a>
            / <a href="#" onclick="Core.loading.wait(); setTimeout(Core.loading.stop, 1000); return false">Load</a>

          </td></tr><tr><td>Send Data</td><td>
            <a href="?_sendData">Data</a>
            / <a href="?_sendJson">JSON</a>

          </td></tr><tr><td>Download</td><td>
            <a href="?_downloadData">Data</a>
            / <a href="?_downloadJson">JSON</a>
            / <a href="?_downloadFile">File</a>

          </td></tr><tr><td>Location</td><td>
            <a href="?_sendLocation">Redirect</a>
            / <a href="?_alert1">Alert Primary</a>
            / <a href="?_alert2">Alert Danger</a>

          </td></tr><tr><td>Abort</td><td>
            <a href="?_abort403">403</a>
            / <a href="?_abort404">404</a>

          </td></tr><tr><td>Unexpected Action</td><td>
            <a href="?___invoke">Uncollable</a>
            / <a href="?_before">Protected</a>
            / <a href="?_abort500">Exception</a>

          </td></tr><tr><td>Undefined Action</td><td>
            <a href="/undefined">Undefined Route</a>
            / <a href="?_undefined">Undefined Method</a>
            / <a href="?_undefined&_routes=1">Show Routes</a>
          </td></tr>
        </tbody>
      </table>
    </div>

    <nav class="mt-4">
      <?= paginate(['total' => 10_000]) ?>
    </nav>
  </div>
</article>

<article class="page-article">
  <div class="page-article-body">Body</div>
</article>

<section class="page-section">
  <header class="page-section-header">
    <h1 class="page-section-title">Header</h1>
  </header>
  <div class="page-section-body">Body</div>
</section>

<section class="page-section">
  <div class="page-section-body">Body</div>
</section>
