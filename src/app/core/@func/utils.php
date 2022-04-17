<?php

use \core\Core;
use \core\config\Setting;
use \core\config\Locale;
use \core\error\ErrorResponse;
use \core\logger\Dump;
use \core\logger\Log;
use \core\storage\Cookie;
use \core\storage\Session;
use \core\utils\html\Pager;
use \core\utils\string\Stash;

////////////////////////////////////////////////////////////////////////////////
// Core

// I do not want to do anything.
function void() {
  return;
}

// Returns the error response.
function abort($code) {
  throw new ErrorResponse($code);
}

// Returns the settings value.
function setting($key) {
  return Setting::$data[$key];
}

// Returns the localized string.
function lc($key, ...$params) {
  if (!$params && $data = &Locale::$data[Core::$lang][$key]) return $data;
  return Locale::get(Core::$lang, $key, ...$params);
}

////////////////////////////////////////////////////////////////////////////////
// Log

// 変数に関する情報をダンプする
function dump(...$data) {
  return Dump::store(...$data);
}

// ログを出力する
function log_debug($data) {
  return Log::log($data, 'DEBUG');
}
function log_info($data) {
  return Log::log($data, 'INFO');
}
function log_warn($data) {
  return Log::log($data, 'WARN');
}
function log_error($data) {
  return Log::log($data, 'ERROR');
}
function log_fatal($data) {
  return Log::log($data, 'FATAL');
}

////////////////////////////////////////////////////////////////////////////////
// File

// ファイルが存在していれば、内容を評価する
function file_try_include($file) {
  return is_file($file) ? include $file : null;
}

// ファイルの内容を評価し、文字列に読み込む
function file_load($file, $checkExists = true) {
  ob_start();
  include $file;
  return ob_get_clean();
}

// ファイルが存在していれば、内容を評価し、文字列に読み込む
function file_try_load($file) {
  return is_file($file) ? file_load($file) : null;
}

// ファイルの内容を文字列に読み込む
function file_read($file) {
  ob_start();
  readfile($file);
  return ob_get_clean();
}

// ファイルが存在していれば、内容を文字列に読み込む
function file_try_read($file) {
  return is_file($file) ? file_read($file) : null;
}

// ファイルが存在していれば、削除する
function file_try_remove($file) {
  return is_file($file) ? unlink($file) : true;
}

// 既存ファイルの内容を検証し、データをファイルに書き込む
function file_write($file, $data, $mode = 0644, $mkdir = true) {
  if (is_file($file) && sha1_file($file) === sha1($data)) return $file;
  if ($mkdir && !is_dir($dir = dirname($file))) mkdir($dir, 0755, true);
  file_put_contents($file, $data, LOCK_EX);
  if ($mode && decoct($mode) != substr(sprintf('%o', fileperms($file)), -4)) chmod($file, $mode);
  return $file;
}

// インクルードパスに対してファイル名を解決する
function file_resolve_path($file, $from = null) {
  if ($file[0] === '/') return $file;
  if ($from && is_file($path = dirname($from) . "/{$file}")) return $path;
  return stream_resolve_include_path($file);
}

////////////////////////////////////////////////////////////////////////////////
// URL

// クエリ文字列を処理し、配列にする
function qs_parse($str) {
  parse_str($str, $array);
  return $array;
}

// クエリ文字列にパラメータを追加する
function qs_merge($str, $params) {
  if (is_string($params)) $params = qs_parse($params);
  return http_build_query(array_merge(qs_parse($str), $params));
}

// URLにクエリパラメータを追加する
function url_merge_query($url, $params) {
  if (is_string($params)) $params = qs_parse($params);
  $split = explode('?', $url);
  $query = http_build_query(array_merge(qs_parse($split[1] ?? ''), $params));
  return $split[0] . ($query ? '?' : '') . $query;
}

// URLに現在のクエリパラメータを引き継ぐ
// function url_next($path) {
//   //return rtrim(preg_replace('/(\?|&)do=.*?(?:&|$)/', '\\1', $path), '?&');
//   if (!$params = Core::$request->params()) return $path;
//   unset($query['do']);
//   return url_merge_query($path, $query);
// }

////////////////////////////////////////////////////////////////////////////////
// PCRE

// 正規表現にマッチした文字列を退避させる
function preg_stash($pattern, $str, &$stash) {
  $stash = new Stash;
  return $stash->stash($pattern, $str);
}

////////////////////////////////////////////////////////////////////////////////
// Helper

function cookie_flash($key) {
  return Cookie::flash($key);
}

function paginate($data, $options = []) {
  return new Pager($data, $options);
}

////////////////////////////////////////////////////////////////////////////////
