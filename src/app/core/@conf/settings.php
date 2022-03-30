<?php

return [

  // Application version
  '--version' => '0.0.4',

  // Environment Mode (development, production)
  '--mode' => 'development',

  // Network Host
  '--host' => '',

  // Accept Languages
  '--lang' => 'ja',

  // Multilingual Localization
  '--multilingual' => [],

  // Crypt Parameters (phrase: 32 digits, iv: 16 digits)
  '--crypt.phrase' => 'a95789d0c1895d855180a4ff920b2cb9', // bin2hex(openssl_random_pseudo_bytes(16))
  '--crypt.iv' => '8a47b1330602fc1c', // bin2hex(openssl_random_pseudo_bytes(8))

  // Cache Driver
  '--cache.driver' => \core\cache\APCu::class,

  // Cache Expires (sec)
  '--cache.expires' => 1,

  // Cache Lock file
  '--cache.lock' => TMP . '/cache.lock',

  // Cache Directory
  '--cache.path' => TMP . '/cache',

  // Log File
  '--log.file' => LOG . '/application.log',

  // Internal Redirection (X-Accel-Redirect, X-SendFile)
  '--action.internalRedirection' => 'X-Accel-Redirect',

  // Overviews
  '--overview.default' => [
    'page-root'   => 'core/@overviews/default/page-root',
    'page-header' => 'core/@overviews/default/page-header',
    'page-navi'   => 'core/@overviews/default/page-navi',
    'page-body'   => 'core/@overviews/default/page-body',
    'page-footer' => 'core/@overviews/default/page-footer',
  ],
];
