<?php

return [

  // Environment Mode (development, production)
  '--mode' => 'production',

  // Encrypt Parameters (phrase: 32 digits, iv: 16 digits)
  '--crypt.phrase' => 'a95789d0c1895d855180a4ff920b2cb9', // bin2hex(openssl_random_pseudo_bytes(16))
  '--crypt.iv' => '8a47b1330602fc1c', // bin2hex(openssl_random_pseudo_bytes(8))

  // Cache Expires (sec)
  '--cache.expires' => 86_400 * 30,
];
