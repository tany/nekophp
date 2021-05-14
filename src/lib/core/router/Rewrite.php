<?php
namespace core\router;

class Rewrite {

  public static function rewritePath($request) {
    $path = $request->getPath();

    if ($request->getHost() === 'www.neko.vm') {
      return "@site{$path}";
    }

    if (!str_starts_with($path, '/@')) return $path;

    $dirs = explode('/', $path, 3);
    $name = substr($dirs[1], 1);

    if ($name === 'user') {
      $dirs[1] = '@user';
    } elseif ($name === 'team') {
      $dirs[1] = '@team';
    } else {
      $dirs[1] = '@error/404';
    }
    array_shift($dirs);
    return join('/', $dirs);
  }

  public static function rewriteAction($request, $action) {
    if ($do = $request->do) {
      return $do[0] !== '_' ? $do : abort(404);
    }
    if ($action === 'REST/show') {
      if ($request->isPut()) return 'updateResource';
      if ($request->isDelete()) return 'deleteResource';
      return 'show';
    }
    if ($action === 'REST/index') {
      if ($request->isPost(true)) return 'createResource';
      if ($request->isDelete()) return 'deleteResources';
      return 'index';
    }
    return $action;
  }
}
