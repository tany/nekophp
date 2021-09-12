<?php

return [

  /**
   * MongoDB Connections
   *
   * @see mongodb://[username:password@]host1[:port1][,...hostN[:portN]][/[defaultauthdb][?options]]
   * @see https://www.php.net/manual/ja/mongodb-driver-manager.construct.php
   * @see https://docs.mongodb.com/manual/reference/connection-string/
   */
  'mongo.clients' => [
    'default' => [
      'uri' => 'mongodb://localhost:27017/',
      'uriOptions' => [
        // 'authSource' => 'admin',
        // 'username' => 'user',
        // 'password' => '****',
      ],
      'driverOptions' => [],
    ],
  ],

  // Overviews
  'mongo.overview.table' => [
    'page-root' => 'core/overviews/default/page-root',
    'page-navi' => 'mongo/overviews/table/page-navi',
    'page-body' => 'core/overviews/table/page-body',
  ],
];
