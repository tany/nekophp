<article class="article">
  <header class="article-header">
    <h1 class="article-title">Examples</h1>
  </header>
  <div class="article-body">

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
            {\lc('--alert.created')}

          </td></tr><tr><td>Javascript</td><td>
            <a href="#" onclick="core.Modal.alert({ message: moment() }); return false">moment</a>
            / <a href="#" onclick="core.Modal.alert({ title: 'Title' }); return false">Alert1</a>
            / <a href="#" onclick="core.Modal.alert({ message: '<s>Message</s>' }); return false">Alert2</a>
            / <a href="#" onclick="core.Modal.alert({ title: 'Title', message: '<s>Message</s>' }); return false">Alert3</a>
            / <a href="#" onclick="core.Modal.confirm({ message: 'Message Only' }); return false">Confirm</a>
            / <a href="#" onclick="core.Loading.start(); setTimeout(core.Loading.stop, 1000); return false">Load</a>

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

    <nav class="mt-md">
      <?= paginate(['total' => 10_000]) ?>
    </nav>
  </div>
</article>

<div class="row">
  <div class="col-8">
    <article class="article">
      <header class="article-header">
        <h1 class="article-title">Article Header</h1>
      </header>
      <div class="article-body">Article Body</div>
    </article>

    <article class="article">
      <div class="article-body">Article Body</div>
    </article>
  </div>
  <div class="col-4">
    <section class="section">
      <header class="section-header">
        <h1 class="section-title">Section Header</h1>
      </header>
      <div class="section-body">Section Body</div>
    </section>

    <section class="section">
      <div class="section-body">Section Body</div>
    </section>
  </div>
</div>
