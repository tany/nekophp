<?php
namespace benchmark;

require '@Benchmark.php';

// @codingStandardsIgnoreFile
new class {

  public static $staticProp;
  public $objectProp;

  public static function staticMethod() {}

  public function objectMethod() {}

  public function __construct() {
    Benchmark::h1('Basic Syntax', 1_000_000);

    Benchmark::h2('if');
    BenchMark::measure('if,elseif' , function() { if (0); elseif (0); elseif (0); elseif(1) $v = 1; });
    BenchMark::measure('match' , function() { $v = match (1) { 0 => 0, 0 => 0,  0 => 0, 1 => 1 }; });
    BenchMark::measure('switch' , function() { switch (1) { case 0: case 0: case 0: case 1: $v = 1; }; });

    Benchmark::h2('bool');
    $null = null;

    BenchMark::measure('isset($undef)' , function() { isset($undef); });
    BenchMark::measure('empty($undef)' , function() { empty($undef); });
    BenchMark::measure('$undef ?? true', function() { $undef ?? true; });
    BenchMark::measure('$null ?? true' , function() use ($null) { $null ?? true; });
    BenchMark::measure('$null ?: true' , function() use ($null) { $null ?: true; });

    $str = str_repeat('*', 100);

    Benchmark::h2('string');
    BenchMark::measure('"{$str}{$str}"', fn() => "{$str}{$str}{$str}{$str}{$str}{$str}");
    BenchMark::measure('$str . $str'   , fn() => $str . $str . $str . $str . $str . $str);

    $str = bin2hex(random_bytes(50));

    Benchmark::h2('search');
    BenchMark::measure('strpos()'      , fn() => strpos($str, 'e'));
    BenchMark::measure('str_contains()', fn() => str_contains($str, 'e'));
    BenchMark::measure('preg_match()'  , fn() => preg_match('/e/', $str));

    $arr1 = range(0, 10);
    $arr2 = range(10, 20);

    Benchmark::h2('array');
    BenchMark::measure('array_merge($arr, $arr)', fn() => array_merge($arr1, $arr2));
    BenchMark::measure('[...$arr, ...$arr]'     , fn() => [...$arr1, ...$arr2]);

    $arr1 = array_fill_keys(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'], 'apple');
    $arr2 = array_fill_keys(['i', 'j', 'k', 'l', 'm', 'n', 'o', 'p'], 'banana');

    Benchmark::h2('hash');
    Benchmark::h3('no_duplicated');
    BenchMark::measure('array_merge($arr, $arr)', fn() => array_merge($arr2, $arr1));
    BenchMark::measure('$array + $array'        , fn() => $arr1 + $arr2);

    $arr1 = array_fill_keys(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'], 'apple');
    $arr2 = array_fill_keys(['a', 'b', 'c', 'd', 'e', 'x', 'y', 'z'], 'banana');

    Benchmark::h3('many_duplicated');
    BenchMark::measure('$array + $array', fn() => $arr1 + $arr2);
    BenchMark::measure('array_merge()'  , fn() => array_merge($arr2, $arr1));

    $arr = array_flip(['a', 'b', 'c', 'd', 'e']);

    Benchmark::h2('extract');
    BenchMark::measure('$arr["k"]', fn() => $arr['a'] && $arr['b'] && $arr['c'] && $arr['d']);
    BenchMark::measure('extract()', fn() => extract($arr));

    $a = $b = $c = $d = 1;

    Benchmark::h2('compact');
    BenchMark::measure('["k" => ]', function() use ($a, $b, $c, $d) { ['a' => $a, 'b' => $b, 'c' => $c, 'd' => $d]; });
    BenchMark::measure('compact()', function() use ($a, $b, $c, $d) { compact('a', 'b', 'c', 'd'); });

    Benchmark::h2('class_property');
    BenchMark::measure('$this->objectProp'  , fn() => $this->objectProp);
    BenchMark::measure('self::$staticProp'  , fn() => self::$staticProp);
    BenchMark::measure('static::$staticProp', fn() => static::$staticProp);

    Benchmark::h2('class_method');
    BenchMark::measure('$this->objectMethod()'  , fn() => $this->objectMethod());
    BenchMark::measure('self::$staticMethod()'  , fn() => self::staticMethod());
    BenchMark::measure('static::$staticMethod()', fn() => static::staticMethod());

    $class   = self::class;
    $method  = 'staticMethod';
    $method2 = "{$class}::{$method}";

    Benchmark::h2('call_user_func');
    BenchMark::measure('$class::$method()'      , fn() => $class::$method());
    BenchMark::measure('[$class, $method]()'    , fn() => [$class, $method]());
    BenchMark::measure('func([$class, $method])', fn() => call_user_func([$class, $method]));
    BenchMark::measure("func('class::method')"  , fn() => call_user_func($method2));

    Benchmark::h2('closure');
    BenchMark::measure('function()', function() { (function() { return 0; })(); });
    BenchMark::measure('fn() =>'   , function() { (fn() => 0)(); });

    Benchmark::h2('file');
    BenchMark::measure('is_file()'    , fn() => is_file(__FILE__));
    BenchMark::measure('is_dir()'     , fn() => is_dir(__DIR__));
    BenchMark::measure('file_exists()', fn() => file_exists(__FILE__));

    print "\n";// End of Benchmark
  }
};
