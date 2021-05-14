<!doctype html>
<html lang="<?= Core::$lang ?>">
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= lc('core.name') ?></title>
<meta name="description" content="<?= lc('core.description') ?>">
<link href="/favicon.png" rel="shortcut icon" type="image/png">
<link href="/favicon.png" rel="apple-touch-icon">
<!--
<script src="/assets/stable/loader.js"></script>
<script>src(
  '/assets/stable/vendor.js','/assets/stable/bundle.js','/assets/stable/vendor.css','/assets/stable/bundle.css',
)</script>
-->
<script src="/assets/stable/vendor.js" defer></script>
<script src="/assets/stable/bundle.js" defer></script>
<link href="/assets/stable/vendor.css" rel="stylesheet">
<link href="/assets/stable/bundle.css" rel="stylesheet">
<link href="/assets/stable/bootstrap-icons.woff" rel="preload" as="font" type="font/woff" crossorigin>
<link href="/assets/stable/fa-solid-900.woff" rel="preload" as="font" type="font/woff" crossorigin>
<body>

<a class="visually-hidden" href="#content">Skip to main content</a>

<div class="page-root">

<div class="page-head">
@partial 'page-head'
</div><!-- /.page-head -->

<div class="page-navi">
@partial 'page-navi'
</div><!-- /.page-navi -->

<div class="page-main">
@partial 'page-main'
</div><!-- /.page-main -->

<div class="page-foot">
@partial 'page-foot'
</div><!-- /.page-foot -->

</div><!-- /.page-root -->

</body>
</html>
