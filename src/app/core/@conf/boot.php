<?php
namespace core;

$core = dirname(__DIR__);

require "{$core}/Loader.php";
require "{$core}/@func/ponyfills.php";
require "{$core}/@func/utils.php";

Loader::initialize();
Loader::include(APP, LIB);
Router::initialize();
