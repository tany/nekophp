<?php

return [

  /**
   * MongoDB Connections
   *
   * @see mongodb://[username:password@]host1[:port1][,host2[:port2][/[database][?options]]
   * @see https://www.php.net/manual/ja/mongodb-driver-manager.construct.php
   * @see https://docs.mongodb.com/manual/reference/connection-string/
   */
  'mongodb.clients' => [
    'default' => 'mongodb://localhost:27017/',

    /* or
    'default' => [
      'hostname' => 'localhost:27017',
      'database' => '',
      'uriOptions'  => [
        'username' => 'username',
        'password' => 'password',
      ],
      'driverOptions' => [],
    ],
    */
  ],
];
