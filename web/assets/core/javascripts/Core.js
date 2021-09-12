'use strict'

module.exports = class Core {

  static clipboard = require('./Clipboard')
  static loading = require('./Loading')
  static modal = require('./Modal')
  static restish = require('./RESTish')

  static nextUrl(url = '') {
    let [path, query] = url.split('?')
    if (path === '') path = location.pathname
    let params = new URLSearchParams((query || '') + '&' + location.search.replace('?', '').replace(/^_\w+(&|$)/, '$1'))
    params = [...new URLSearchParams(params).entries()].reduce((obj, e) => ({...obj, [e[0]]: e[1]}), {})
    query = Object.entries(params).map((e) => `${e[0]}=${e[1]}`).join('&').replace(/=(&|$)/, '$1')
    return query ? `${path}?${query}` : path
  }

  static disableButton(el) {
    el.classList.add('disabled')
    setTimeout(function(el) { el.classList.remove('disabled') }, 2000, el)
  }

  static disableAjaxSubmit(el) {
    el.classList.add('disabled')
    $('.js-rest-create, .js-rest-update').off('click')
  }
}
