<?php
namespace core\view;

use \core\storage\Cache;

class Compiler {

  protected static $current;

  public static function compile($file) {
    self::$current = $file;
    return self::cache($file);
  }

  protected static function cache($file) {
    $path = substr($file, strlen(APP) + 1);
    $hash = substr(sha1(str_pop($path, '/')), 0, 2);
    $path = Cache::$path . "/@views/{$hash}/" . strtr($path, '/', '-');

    if (Cache::$keep && is_file($path)) return $path;

    return file_write($path, self::parse(file_read($file)));
  }

  protected static function parse($data) {
    // stash/replace
    $data = preg_stash('/<\?.*?\?\>/s', trim($data), $stash);

    // comment
    //$data = preg_replace('/(^|\n) *\/\/ *@.*(\n|$)/m', '', $data);
    $data = preg_replace('/^ *\/\/ *@.*$/m', '', $data);

    // @syntax
    //$data = preg_replace_callback('/(?:^|\n) *@(\w+) ?(.*)(?:\n|$)/m', [self::class, 'compileSyntax'], $data);
    $data = preg_replace_callback('/^ *@(\w+) ?(.*)$/m', [self::class, 'compileSyntax'], $data);

    // {$var}
    $data = preg_replace_callback('/\{(\$\w.*?)\}/', [self::class, 'compileVar'], $data);

    // {\exp}
    $data = preg_replace_callback('/\{(\\\\\w.*?)\}/', [self::class, 'compileExp'], $data);

    // adjust _
    $data = preg_replace('/ _$/m', "\n", $data);

    // stash/restore
    $data = $stash->restore($data);

    return $data;
  }

  protected static function compileIsset($exp) {
    if (preg_match('/^\$[\w\->\[\]\"\']+$/', $exp)) return "{$exp} ?? ''";
    return $exp;
  }

  protected static function compileVar($m) {
    return "<?= htmlspecialchars({$m[1]}, ENT_QUOTES) ?>";
  }

  protected static function compileExp($m) {
    return "<?= htmlspecialchars({$m[1]}, ENT_QUOTES) ?>";
  }

  protected static function compileSyntax($m) {
    return match ($m[1]) {
      'end'     => "<?php } ?>",
      'if'      => "<?php if (" . self::compileIsset($m[2]) . ") { ?>",
      'else'    => "<?php } else { ?>",
      'elseif'  => "<?php } elseif (" . self::compileIsset($m[2]) . ") { ?>",
      'partial' => "<?php \$view->renderPartial({$m[2]}, '" . self::$current . "') ?>",
      'yield'   => "<?php print \$view->yield; \$view->yield = null ?>",
      'render'  => "<?php \$view->renderFrom({$m[2]}, '" . self::$current . "') ?>",
      'foreach' => "<?php foreach ({$m[2]}) { ?>",
      'for'     => "<?php for ({$m[2]}) { ?>",
    };
  }
}
