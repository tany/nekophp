'use strict'

// location = /assets/loader.js {
//   add_header Cache-Control "no-cache,max-age=0,must-revalidate" always;
//   add_header Pragma "no-cache" always; # IE
// }
// location ~ ^/(assets)/stable/(.*)$ {
//   add_header Cache-Control "public,max-age=2592000,immutable";
// }

global.src = function(...path) {
  path.forEach(p => document.write(
    /\.js$/.test(p)
    ? '<script src="' + p + '?_r=@REVISION"><\/script>'
    : '<link href="' + p + '?_r=@REVISION" rel="stylesheet">'
  ))
}
