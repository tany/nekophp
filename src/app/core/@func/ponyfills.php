<?php

////////////////////////////////////////////////////////////////////////////////
// Variable

// 変数が空（ゼロサイズ）であるかどうかを検査する
function blank($data) {
  return !($data || ($data === [] ? false : strlen($data)));
}

// 配列が連想配列かどうかを調べる
function is_assoc_array($data) {
  return is_array($data) && $data !== array_values($data);
}

// 配列が添字配列かどうかを調べる
function is_index_array($data) {
  return is_array($data) && $data === array_values($data);
}

// foreach を使用してたどれるかどうかを調べる
function is_traversable($data) {
  return is_array($data) || $data instanceof \Traversable || $data instanceof \stdClass;
}

// 変数が空（ゼロサイズ）であるかどうかを検査する
function present($data) {
  return $data || ($data === [] ? false : strlen($data));
}

// 変数が空（ゼロサイズ）であれば null を返す
function presence($data) {
  return $data || ($data === [] ? false : strlen($data)) ? $data : null;
}

////////////////////////////////////////////////////////////////////////////////
// String

// キャメルケース文字列に変換する
function str_camel($str, $replaceMarks = false) {
  return lcfirst(preg_replace('/ /', '', ucwords(preg_replace('/[\-_]+/', ' ', $str))));
}

// ハイフン区切り文字列に変換する
function str_chain($str) {
  return preg_replace("/[\s\-_]+/", '-', strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $str)));
}

// 文字列を読みやすい感じにする
function str_human($str) {
  return ucfirst(str_space(strtr($str, '_', ' ')));
}

// パスカルケース文字列に変換する
function str_pascal($str) {
  return preg_replace('/ /', '', ucwords(preg_replace('/[\-_]+/', ' ', $str)));
}

// 指定した文字列から最後までを取り除く
function str_pop($haystack, ...$needles) {
  foreach ($needles as $needle) {
    if (($pos = strpos($haystack, $needle)) !== false) $haystack = substr($haystack, 0, $pos);
  }
  return $haystack;
}

// 指定した文字列(逆順検索)から最後までを取り除く
function str_rpop($haystack, ...$needles) {
  foreach ($needles as $needle) {
    if (($pos = strrpos($haystack, $needle)) !== false) $haystack = substr($haystack, 0, $pos);
  }
  return $haystack;
}

// 先頭から指定した文字列(逆順検索)までを取り除く
function str_rshift($haystack, ...$needles) {
  foreach ($needles as $needle) {
    if (($pos = strrpos($haystack, $needle)) !== false) $haystack = substr($haystack, $pos + strlen($needle));
  }
  return $haystack;
}

// 先頭から指定した文字列までを取り除く
function str_shift($haystack, ...$needles) {
  foreach ($needles as $needle) {
    if (($pos = strpos($haystack, $needle)) !== false) $haystack = substr($haystack, $pos + strlen($needle));
  }
  return $haystack;
}

// アンダースコア区切り文字列に変換する
function str_snake($str) {
  return preg_replace("/[\s\-_]+/", '_', strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $str)));
}

// スペース区切り文字列に変換する
function str_space($str, $replaceMarks = false) {
  return preg_replace("/[\s\-_]+/", ' ', strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $str)));
}

// 検索文字列に一致した文字列を1回だけ置換する
function str_sub($search, $replace, $subject, $limit = 1) {
  if ($limit === 1) {
    $pos = strpos($subject, $search);
    return $pos === false ? $subject : substr_replace($subject, $replace, $pos, strlen($search));
  }
  return preg_replace('/' . preg_quote($search, '/') . '/', $replace, $subject, $limit);
}

////////////////////////////////////////////////////////////////////////////////
// Multibyte String

// 文字列の一部を置換する
function mb_substr_replace($string, $replace, $offset, $length = null) {
  return mb_substr($string, 0, $offset) . $replace . ($length === null ? '' : mb_substr($string, $length + 1));
}


////////////////////////////////////////////////////////////////////////////////
// HTML

// 改行文字を改行タグに変換する
function br($str) {
  return str_replace("\n", '<br />', $str);
}

// htmlspecialchars() のエイリアス
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES);
}

// htmlspecialchars() + nl2br() のエイリアス
function hbr($str) {
  return nl2br(htmlspecialchars($str, ENT_QUOTES));
}

////////////////////////////////////////////////////////////////////////////////
// Number

// 指定した有効桁数で数字をフォーマットする
function number_sigfig($num, $figures = 3) {
  return number_format($num, $figures - strlen(floor($num)));
}

// 数字をバイト単位へフォーマットする
function size_format($num, $decimals = 0) {
  if ($num >= 1024 ** 4) return number_format($num / 1024 ** 4, $decimals) . ' TB';
  if ($num >= 1024 ** 3) return number_format($num / 1024 ** 3, $decimals) . ' GB';
  if ($num >= 1024 ** 2) return number_format($num / 1024 ** 2, $decimals) . ' MB';
  if ($num >= 1024 ** 1) return number_format($num / 1024 ** 1, $decimals) . ' KB';
  //return ($num == 1) ? '1 byte' : "{$num} bytes";
  return '1 KB';
}

// 数字をキロ単位へフォーマットする
function size_kb($num, $decimals = 0) {
  return number_format(ceil($num / 1024 ** 1), $decimals) . ' KB';
}

// 数字をメガバイト単位へフォーマットする
function size_mb($num, $decimals = 0) {
  return number_format(ceil($num / 1024 ** 2), $decimals) . ' MB';
}

////////////////////////////////////////////////////////////////////////////////
// Array

// 連想キーと要素との関係を維持しつつ配列を昇順にソートする
function array_asort($array, $flags = SORT_REGULAR) {
  asort($array, $flags);
  return $array;
}

// 連想キーと要素との関係を維持しつつ配列を降順にソートする
function array_arsort($array, $flags = SORT_REGULAR) {
  arsort($array, $flags);
  return $array;
}

// 二つ以上の配列から、要素の組み合わせ配列を生成する
function array_combinations(...$arrays) {
  if (!$data = array_shift($arrays)) return [];
  if (!$arrays) {
    foreach ($data as $d) $ret[] = [$d];
    return $ret;
  }
  if (!$next = array_combinations(...$arrays)) return [];
  foreach ($data as $d) foreach ($next as $n) $ret[] = array_merge([$d], $n);
  return $ret;
}

// 配列の空要素などをフィルタリングする
function array_delete($array, $needles) {
  foreach ($array as $k => $v) if (in_array($v, $needles, true)) unset($array[$k]);
  return $array;
}

// 指定した位置にある配列の要素を返す
function array_dig($array, ...$keys) {
  if (($key = array_shift($keys)) === null) return $array;
  if (strpos($key, '.')) return array_dig($array, ...explode('.', $key));
  if (is_array($array) && isset($array[$key])) return array_dig($array[$key], ...$keys);
  return null;
}

// コールバック関数を使用して、配列の要素を再帰的にフィルタリングする
function array_filter_r($array, $callback = 'presence') {
  foreach ($array as &$value) {
    if (is_array($value)) $value = array_filter_r($value, $callback);
  }
  return array_filter($array, $callback);
}

// 配列をキーで昇順にソートする
function array_ksort($array, $flags = SORT_STRING | SORT_FLAG_CASE) {
  ksort($array, $flags);
  return $array;
}

// 配列をキーで降順にソートする
function array_krsort($array, $flags = SORT_STRING | SORT_FLAG_CASE) {
  krsort($array, $flags);
  return $array;
}

// 配列の要素にコールバック関数を再帰的に適用する
function array_map_r($array, $callback, $mode = true) {
  foreach ($array as &$value) {
    if (is_traversable($value)) $value = array_map_r($value, $callback);
    $value = $callback($value);
  }
  return $array;
}

// コールバック関数 present() を使用して、配列の要素をフィルタリングする
function array_presence($array) {
  return array_filter($array, 'present');
}

// 配列を降順にソートする
function array_rsort($array, $flags = SORT_STRING | SORT_FLAG_CASE) {
  rsort($array, $flags);
  return $array;
}

// 配列を昇順にソートする
function array_sort($array, $flags = SORT_STRING | SORT_FLAG_CASE) {
  sort($array, $flags);
  return $array;
}

////////////////////////////////////////////////////////////////////////////////
// Object

function object_blank($object) {
  return !object_present($object);
}

function object_present($object) {
  if ($object instanceof \stdClass) return count((array)$object) ? true : false;
  if ($object instanceof \Hash) return $object->count() ? true : false;
  return $object === null ? false : present($object);
}

function object_presence($object) {
  return object_present($object) ? $object : null;
}

////////////////////////////////////////////////////////////////////////////////
// Date and Time

// 指定した ISO 8601 日付 の DateTime オブジェクトを返す
function date_create_from_iso($str) {
  if (!preg_match('/^\d{4}-\d\d-\d\dT\d\d:\d\d:\d\d\+\d\d:\d\d$/', $str)) return;

  static $tz;
  $tz ??= new \DateTimeZone(date_default_timezone_get());
  return date_create($str)->setTimezone($tz);
}

////////////////////////////////////////////////////////////////////////////////
// JSON

// 値を整形した JSON 形式にして返す
function json_encode_pretty($data) {
  $json = json_encode($data, JSON_THROW_ON_ERROR
    | JSON_PARTIAL_OUTPUT_ON_ERROR
    | JSON_PRESERVE_ZERO_FRACTION
    | JSON_PRETTY_PRINT
    | JSON_UNESCAPED_LINE_TERMINATORS
    | JSON_UNESCAPED_SLASHES
    | JSON_UNESCAPED_UNICODE);
  return preg_replace_callback('/^ +/m', fn($sp) => str_repeat(' ', strlen($sp[0]) / 2), $json);
}

////////////////////////////////////////////////////////////////////////////////
// File

// パスの最後にある拡張子の部分を返す
function extname($path, $dot = '.') {
  if (!$pos = strrpos($path, '.')) return null;
  return strpos($ext = substr($path, $pos + 1), '/') ? null : "{$dot}{$ext}";
}

// パスの最後にある名前に、文字列が含まれるかを調べる
function basename_contains($path, $needle) {
  return strpos($path, $needle, strrpos($path, '/')) !== false;
}


////////////////////////////////////////////////////////////////////////////////
// List Segments

// ディレクトリ内のディレクトリ一覧を返す
function ls_dir($dir, $path = false) {
  if ($path === true) $path = "{$dir}/";
  foreach (scandir($dir) as $file) {
    if ($file[0] === '.') continue;
    if (is_dir("{$dir}/{$file}")) $list[] = "{$path}{$file}";
  }
  return $list ?? [];
}

// ディレクトリ内のファイル一覧を返す
function ls_file($dir, $path = false) {
  if ($path === true) $path = "{$dir}/";
  foreach (scandir($dir) as $file) {
    if ($file[0] === '.') continue;
    if (is_file("{$dir}/{$file}")) $list[] = "{$path}{$file}";
  }
  return $list ?? [];
}

////////////////////////////////////////////////////////////////////////////////
// Output Buffering

// 現在のバッファの内容を取得し、出力バッファをクリア(消去)する
function ob_capture() {
  $buf = ob_get_contents();
  ob_clean();
  return $buf;
}

////////////////////////////////////////////////////////////////////////////////
// Class

// 与えられたクラスの親クラスをソートして返す
function class_parents_sort($class) {
  static $cache = [];
  return $cache[$class] ??= array_reverse(class_parents($class));
}

// 指定したクラスが使っているトレイトを再帰的に探す
function class_uses_recursive($class) {
  static $cache = [];
  if (isset($cache[$class])) return $cache[$class];

  $traits = [];
  foreach (class_uses($class) as $trait) {
    $traits += class_uses_recursive($trait);
    $traits[$trait] = $trait;
  }
  return $cache[$class] = $traits;
}

// 指定したクラスが使っているトレイトを、親クラスも含め再帰的に探す
function class_uses_all($class) {
  static $cache = [];
  if (isset($cache[$class])) return $cache[$class];

  $traits = [];
  foreach (class_parents_sort($class) as $parent) {
    $traits += class_uses_recursive($parent);
  }
  $traits += class_uses_recursive($class);
  return $cache[$class] = $traits;
}
