<?php
namespace core;

require '../src/lib/core/Loader.php';

Loader::initialize(dirname(__DIR__));
Loader::include(LIB, APP);

Router::initialize();
Router::process(new Request);
