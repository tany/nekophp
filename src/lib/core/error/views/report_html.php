<!doctype html>
<html lang="ja">
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= $this->title ?></title>
<link href="/assets/core/stylesheets/error-report.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
<body>

<h1 class="error-title"><?= $this->title ?></h1>
<h2 class="error-message"><?= $this->message ?></h2>

<?php foreach ($this->views as $view) { ?>
<div class="error-section">
  <div class="error-section-header"><?= $view['name'] ?></div>
  <div class="error-section-body">
    <table class="error-table">
      <?php foreach ($view['data'] as $data) { ?>
      <tr class="<?= ($data['css'] ?? '') ?>">
        <td data-num="<?= $data['key'] ?>"></td>
        <td><?= h($data['val']) ?></td>
      <?php } ?>
    </table>
  </div>
</div>
<?php } ?>

<?= str_repeat(' ', 512) ?>
</body>
</html>
