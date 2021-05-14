<?php
namespace core\error;

class ErrorCode {

  public const TEXTS = [
    0                   => 'Exception',             # 0
    E_ERROR             => 'Error',                 # 1
    E_WARNING           => 'Warning',               # 2
    E_PARSE             => 'Parse Error',           # 4
    E_NOTICE            => 'Notice',                # 8
    E_CORE_ERROR        => 'Core Error',            # 16
    E_CORE_WARNING      => 'Core Warning',          # 32
    E_COMPILE_ERROR     => 'Compile Error',         # 64
    E_COMPILE_WARNING   => 'Compile Warning',       # 128
    E_USER_ERROR        => 'User Error',            # 256
    E_USER_WARNING      => 'User Warning',          # 512
    E_USER_NOTICE       => 'User Notice',           # 1024
    E_STRICT            => 'Strict Notice',         # 2048
    E_RECOVERABLE_ERROR => 'Recoverable Error',     # 4096
    E_DEPRECATED        => 'Deprecated Error',      # 8192
    E_USER_DEPRECATED   => 'User Deprecated Error', # 16384
    E_ALL               => 'All Error',             # 32767
  ];
}
