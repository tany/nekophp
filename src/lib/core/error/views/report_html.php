<!doctype html>
<html lang="ja">
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= h($this->title) ?></title>
<link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
<style><?= preg_replace('/\s+/', ' ', file_load(__DIR__ . '/style.css')); ?></style>
<body>

<div class="page-head">
  <div class="container">
  <?= $this->type ?>
  </div>
</div>

<div class="page-navi">
  <div class="container">
  <?= h($this->title) ?>
  </div>
</div>

<div class="page-body container" role="main">

  <?php foreach ($this->views as $view) { ?>
  <div class="data-name"><?= $view['name'] ?></div>
  <div class="data-view">
    <table class="data-table">

    <?php foreach ($view['data'] as $data) { ?>
    <tr class="<?= ($data['css'] ?? '') ?>">
      <td data-num="<?= $data['key'] ?>"></td>
      <td><?= h($data['val']) ?></td>
    <?php } ?>

    </table>
  </div>
  <?php } ?>

</div>

</body>
</html>
<?= str_repeat(' ', 512) ?>
