<?php

////////////////////////////////////////////////////////////////////////////////
// String

// キャメルケース文字列に変換する
function str_camel($str) {
  return str_replace(' ', '', ucwords(preg_replace('/[_\-]+/', ' ', preg_replace('/([^a-zA-Z0-9]+)/', '$1 ', $str))));
}

// ハイフン区切り文字列に変換する
function str_kebab($str) {
  return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $str));
}

// アンダースコア区切り文字列に変換する
function str_snake($str) {
  return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $str));
}

// スペース区切り文字列に変換する
function str_space($str) {
  return strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $str));
}

// 文字列を読みやすい感じにする
function str_human($str) {
  return ucfirst(str_space(strtr($str, '_', ' ')));
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

// 先頭から指定した文字列までを取り除く
function str_shift($haystack, ...$needles) {
  foreach ($needles as $needle) {
    if (($pos = strpos($haystack, $needle)) !== false) $haystack = substr($haystack, $pos + strlen($needle));
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

// 配列が添字配列かどうかを調べる
function is_index_array($data) {
  return is_array($data) && $data === array_values($data);
}

// 配列が連想配列かどうかを調べる
function is_assoc_array($data) {
  return is_array($data) && $data !== array_values($data);
}

// 配列の空要素をフィルタリングする
function array_clean($data) {
  foreach ($data as $k => $v) if ($v === null || $v === '' || $v === []) unset($data[$k]);
  return $data;
}

// 配列の空要素などをフィルタリングする
function array_delete($data, $needles) {
  foreach ($data as $k => $v) if (array_search($v, $needles, true) !== false) unset($data[$k]);
  return $data;
}

// 指定した位置にある配列の要素を返す
function array_dig($array, ...$keys) {
  if (($key = array_shift($keys)) === null) return $array;
  if (strpos($key, '.')) return array_dig($array, ...explode('.', $key));
  if (is_array($array) && isset($array[$key])) return array_dig($array[$key], ...$keys);
  return null;
}

// キーを使用し、配列の要素にコールバック関数を適用する
function array_kmap(callable $closure, array $array) {
  foreach ($array as $key => $val) $ret[] = $closure($key, $val);
  return $ret ?? [];
}

// 配列の要素にコールバック関数を再帰的に適用する
function array_map_recursive(callable $closure, array $array) {
  foreach ($array as $k => $v) {
    if (is_array($v)) $buf[$k] = array_map_recursive($closure, $v);
    else $buf[$k] = $closure($v);
  }
  return $buf ?? [];
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

////////////////////////////////////////////////////////////////////////////////
// JSON

// 値を整形した JSON 形式にして返す
function json_encode_pretty($data) {
  return json_encode($data, JSON_THROW_ON_ERROR
    | JSON_PARTIAL_OUTPUT_ON_ERROR
    | JSON_PRESERVE_ZERO_FRACTION
    | JSON_PRETTY_PRINT
    | JSON_UNESCAPED_LINE_TERMINATORS
    | JSON_UNESCAPED_SLASHES
    | JSON_UNESCAPED_UNICODE);
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
