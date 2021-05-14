<h1 class="h3">MongoDB</h1>

<form class="p-3 border bg-light" method="post" action="" style="max-width: 30rem;">
  <label for="data-name" class="form-label">Connection</label>
  <select class="form-select" id="data-name" name="data[name]" aria-label="client name">
    @foreach $this->clients as $key => $val
    <option value="{$key}">[{$key}] {$val['hostname']}{$val['database'] ? '/' . $val['database'] : ''}</option>
    @end
  </select>

  <div class="mt-4">
    <button type="submit" class="btn btn-primary">Connect</button>
  </div>
</form>
