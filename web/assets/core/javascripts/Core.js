'use strict'

module.exports = class Core {

  static nextUrl(url = '') {
    let [path, query] = url.split('?')
    if (path === '') path = location.pathname
    let params = new URLSearchParams(location.search.replace(/(\?|&)do=.*?(&|$)/, '$1') + '&' + (query || ''))
    params = [...new URLSearchParams(params).entries()].reduce((obj, e) => ({...obj, [e[0]]: e[1]}), {})
    query = Object.entries(params).map((e) => `${e[0]}=${e[1]}`).join('&').replace(/\?$/, '')
    return query ? `${path}?${query}` : path
  }

  static disableButton(el) {
    el.classList.add('disabled')
    setTimeout(function(el) { el.classList.remove('disabled') }, 3000, el)
  }

  static waitLoad() {
    $('body').append('<div class="d-flex align-items-center justify-content-center core-loading">'
      + '<div class="spinner-border text-secondary" role="status">'
      + '<span class="visually-hidden">Loading...</span></div></div>'
    )
  }

  static endLoad() {
    $('.core-loading').remove()
  }

  static confirm(str) {
    if (!str) return true
    return confirm(str)
  }

  static doneRESTish(data, status, xhr) {
    console.log('[done]', data, status, xhr)

    if ($_ = xhr.getResponseHeader('location')) {
      location.href = (xhr.status === 201) ? $_ : Core.nextUrl($_)
    }
    Core.endLoad()
  }

  static failRESTish(xhr, status, error) {
    console.log('[fail]', xhr, status, error)

    if (!xhr.responseJSON) return alert(xhr.responseText || error)
    let data = xhr.responseJSON
    if (data.items) data.error += "\n--\n"
    for (let item of data.items) data.error += item + "\n"
    alert(data.error)

    Core.endLoad()
  }
}
