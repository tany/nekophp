<?php

/**
 * Usage: phpmd [options] [filename|directory] [report format] [ruleset file]
 *   --minimumpriority - The rule priority threshold; rules with lower priority than they will not be used.
 *   --reportfile - Sends the report output to the specified file, instead of the default output target STDOUT.
 *   --suffixes - Comma-separated string of valid source code filename extensions, e.g. php,phtml.
 *   --exclude - Comma-separated string of patterns that are used to ignore directories.
 *   --strict - Also report those nodes with a @SuppressWarnings annotation.
 *   --ignore-violations-on-exit - will exit with a zero code, even if any violations are found.
 */
return new class {

  public function __construct() {
    $cmd[] = 'vendor/bin/phpmd';
    //$cmd[] = join(' ', $args);
    $cmd[] = 'conf,src,web text --suffixes php src/test/code/phpmd.xml';

    $cmd = filter_var(join(' ', array_filter($cmd)));
    $res = preg_replace('/^#\d+.*\n/m', '', shell_exec($cmd));

    # PHP8: match
    $res = preg_replace('/^\s*-\s*Unexpected token:.*\n/m', '', $res);

    print "# PHPMD: {$cmd}\n\n";
    print $res ?: "  PHPMD: ok\n";
    print "\n";
  }
};
