<?php
namespace benchmark;

require 'src/app/core/@conf/boot.php';

// @codingStandardsIgnoreFile
new class extends BenchmarkBase {

  public static $staticProp;
  public $objectProp;

  public static function staticMethod() {
  }

  public function objectMethod() {
  }

  public function __invoke() {
    $this->h1('Basic Syntax', 1_000_000);

    $this->h2('bool');

    $null = null;
    $this->measure('isset($undef)' , fn() => isset($undef));
    $this->measure('empty($undef)' , fn() => empty($undef));
    $this->measure('$undef ?? true', fn() => $undef ?? true);
    $this->measure('$null ?? true' , fn() => $null ?? true);
    $this->measure('$null ?: true' , fn() => $null ?: true);

    $this->h2('string');

    $str = str_repeat('*', 100);
    $this->measure('"{$str}{$str}"', fn() => "{$str}{$str}{$str}{$str}{$str}{$str}");
    $this->measure('$str . $str'   , fn() => $str . $str . $str . $str . $str . $str);

    $this->h2('search');

    $str = bin2hex(random_bytes(50));
    $this->measure('strpos()'      , fn() => strpos($str, 'e'));
    $this->measure('str_contains()', fn() => str_contains($str, 'e'));
    $this->measure('preg_match()'  , fn() => preg_match('/e/', $str));

    $this->h2('array');

    $arr1 = range(0, 10);
    $arr2 = range(10, 20);
    $this->measure('array_merge($arr, $arr)', fn() => array_merge($arr1, $arr2));
    $this->measure('[...$arr, ...$arr]'     , fn() => [...$arr1, ...$arr2]);

    $this->h2('hash:no_duplicated');

    $arr1 = array_fill_keys(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'], 'apple');
    $arr2 = array_fill_keys(['i', 'j', 'k', 'l', 'm', 'n', 'o', 'p'], 'banana');
    $this->measure('array_merge($arr, $arr)', fn() => array_merge($arr2, $arr1));
    $this->measure('$array + $array'        , fn() => $arr1 + $arr2);

    $this->h2('hash:many_duplicated');

    $arr1 = array_fill_keys(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'], 'apple');
    $arr2 = array_fill_keys(['a', 'b', 'c', 'd', 'e', 'x', 'y', 'z'], 'banana');
    $this->measure('$array + $array', fn() => $arr1 + $arr2);
    $this->measure('array_merge()'  , fn() => array_merge($arr2, $arr1));

    $this->h2('extract');

    $arr = array_flip(['a', 'b', 'c', 'd', 'e']);
    $this->measure('$arr["k"]', fn() => $arr['a'] && $arr['b'] && $arr['c'] && $arr['d']);
    $this->measure('extract()', fn() => extract($arr));

    $this->h2('compact');

    $a = $b = $c = $d = 1;
    $this->measure('["k" => ]', fn() => ['a' => $a, 'b' => $b, 'c' => $c, 'd' => $d]);
    $this->measure('compact()', function() use ($a, $b, $c, $d) { compact('a', 'b', 'c', 'd'); });

    $this->h2('class_property');

    $this->measure('$this->objectProp'  , fn() => $this->objectProp);
    $this->measure('self::$staticProp'  , fn() => self::$staticProp);
    $this->measure('static::$staticProp', fn() => static::$staticProp);

    $this->h2('class_method');

    $this->measure('$this->objectMethod()'  , fn() => $this->objectMethod());
    $this->measure('self::$staticMethod()'  , fn() => self::staticMethod());
    $this->measure('static::$staticMethod()', fn() => static::staticMethod());

    $this->h2('call_user_func');

    $class   = self::class;
    $method  = 'staticMethod';
    $method2 = "{$class}::{$method}";
    $this->measure('$class::$method()'      , fn() => $class::$method());
    $this->measure('[$class, $method]()'    , fn() => [$class, $method]());
    $this->measure('func([$class, $method])', fn() => call_user_func([$class, $method]));
    $this->measure("func('class::method')"  , fn() => call_user_func($method2));

    $this->h2('closure');

    $this->measure('function()', function() { (function() { return 0; })(); });
    $this->measure('fn() =>'   , function() { (fn() => 0)(); });

    $this->h2('file');

    $this->measure('is_file()'    , fn() => is_file(__FILE__));
    $this->measure('is_dir()'     , fn() => is_dir(__DIR__));
    $this->measure('file_exists()', fn() => file_exists(__FILE__));

    print "\n";
  }
};
