<?php

/**
 * Usage: phpcs [-nwlsaepqvi] [-d key[=value]] [--colors] [--no-colors]
 *   -s   Show sniff codes in all reports
 */
return new class {

  public function __construct() {
    $cmd[] = 'vendor/bin/phpcs';
    //$cmd[] = join(' ', $args);
    $cmd[] = '--standard=' . __DIR__ . '/phpcs.xml ./';

    $cmd = filter_var(join(' ', array_filter($cmd)));
    $res = shell_exec($cmd);

    print "# PHPCS: {$cmd}\n\n";
    print $res ?: "  PHPCS: ok\n";
    print "\n";
  }
};
