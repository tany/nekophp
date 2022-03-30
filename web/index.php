<?php
namespace core;

require '../src/app/core/Loader.php';

Loader::initialize(dirname(__DIR__));
Loader::include(APP, LIB);
Router::initialize();
Router::process(new Request);
