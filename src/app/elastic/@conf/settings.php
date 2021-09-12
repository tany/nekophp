<?php

return [

  /**
   * Elasticsearch Connections
   *
   * @see [http[s]://][username:password@]host[:port][/path]
   * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/host-config.html
   */
  'elastic.clients' => [
    'default' => [
      'http://localhost:9200',
    ],
  ],

  // Overviews
  'elastic.overview.table' => [
    'page-root' => 'core/overviews/default/page-root',
    'page-navi' => 'elastic/overviews/table/page-navi',
    'page-body' => 'core/overviews/table/page-body',
  ],
];
