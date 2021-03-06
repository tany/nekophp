<!doctype html>
<html lang="<?= Core::$lang ?>">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= lc('--application.name') ?></title>
<meta name="description" content="<?= lc('--application.description') ?>">
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
<!-- <link href="/assets/stable/bootstrap-icons.woff" rel="preload" as="font" type="font/woff" crossorigin=""> -->
<link href="/assets/stable/bootstrap-icons.woff" rel="stylesheet" type="font/woff" crossorigin="">
</head>
<body>

<a class="visually-hidden" href="#main">Skip to main content</a>

<div class="page-container">

@partial 'page-header'
@partial 'page-navi'
@partial 'page-body'
@partial 'page-footer'

</div><!-- /.page-container -->

</body>
</html>
