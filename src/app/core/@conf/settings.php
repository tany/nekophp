<?php

return [

  // Environment Mode (development, production)
  'core.mode' => 'development',

  // Network Host
  'core.host' => '',

  // Accept Languages
  'core.lang' => 'ja',

  // Multilingual Localization
  'core.multilingual' => [],

  // Crypt Parameters (phrase: 32 digits, iv: 16 digits)
  'core.crypt.phrase' => 'a95789d0c1895d855180a4ff920b2cb9', // bin2hex(openssl_random_pseudo_bytes(16))
  'core.crypt.iv' => '8a47b1330602fc1c', // bin2hex(openssl_random_pseudo_bytes(8))

  // Cache Driver
  'core.cache.driver' => \core\cache\APCu::class,

  // Cache Expires (sec)
  'core.cache.expires' => 1,

  // Cache Lock file
  'core.cache.lock' => TMP . '/cache.lock',

  // Cache Directory
  'core.cache.path' => TMP . '/cache',

  // Log File
  'core.log.file' => LOG . '/application.log',
];
