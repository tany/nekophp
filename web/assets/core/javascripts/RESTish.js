'use strict'

module.exports = class RESTish {

  static setListSelect(el) {
    el.on('click', function(ev) {
      let name = this.dataset.target ?? 'id'
      el.closest('table').find(`input[name="${name}"]`).prop('checked', true)
      ev.preventDefault()
      return false
    })
  }

  static setListDeselect(el) {
    el.on('click', function(ev) {
      let name = this.dataset.target ?? 'id'
      el.closest('table').find(`input[name="${name}"]`).prop('checked', false)
      ev.preventDefault()
      return false
    })
  }

  static setListDelete(el) {
    el.on('click', function(ev) {
      let name = this.dataset.target ?? 'id'
      let values = el.closest('table').find(`input[name="${name}"]:checked`).map((_, el) => $(el).val()).get()
      if (values.length === 0) return false

      $.ajax({ data: { _method: 'DELETE', id: values }, dataType: 'json', type: 'POST', url: this.dataset.href ?? '.' })
        .then(RESTish.done).catch(RESTish.fail)
      ev.preventDefault()
    })
  }

  static setAjaxSubmit(el) {
    let form = el.closest('form')
    if (!form.attr('action')) form.attr('action', '?')

    el.on('click', function(ev) {
      form.ajaxSubmit({ dataType: 'json' }).data('jqxhr')
        .then(RESTish.done).catch(RESTish.fail)
      Core.disableButton(this)
      ev.preventDefault()
    })
  }

  static setCreate(el) {
    let form = el.closest('form')
    if (!form.attr('action')) form.attr('action', '.')

    el.on('click', function(ev) {
      form.ajaxSubmit({ dataType: 'json' }).data('jqxhr')
        .then(RESTish.done).catch(RESTish.fail)
      Core.disableButton(this)
      ev.preventDefault()
    })
    window.stop() // for LiveReload
  }

  static setUpdate(el) {
    let form = el.closest('form')
    if (!form.attr('action')) form.attr('action', '?')
    form.append('<input type="hidden" name="_method" value="PUT">')

    el.on('click', function(ev) {
      form.ajaxSubmit({ dataType: 'json' }).data('jqxhr')
        .then(RESTish.done).catch(RESTish.fail)
      Core.disableButton(this)
      ev.preventDefault()
    })
    window.stop() // for LiveReload
  }

  static setDelete(el) {
    el.on('click', function(ev) {
      let url = this.getAttribute('href') ?? this.dataset.href ?? '?'

      Core.modal.confirm({ message: this.dataset.confirm }).then(() => {
        $.ajax({ data: { _method: 'DELETE' }, dataType: 'json', type: 'POST', url: url })
          .then(RESTish.done).catch(RESTish.fail)
        Core.disableButton(this)
      }).catch(() => {
      })
      ev.preventDefault()
    })
  }

  static done(data, status, xhr) {
    console.log('[done]', data, status, xhr)
    Core.loading.stop()

    let params = { type: 'success', location: xhr.getResponseHeader('location') }

    if (xhr.responseJSON) {
      params = Object.assign(params, xhr.responseJSON)
    } else if (xhr.responseText) {
      params.title = xhr.responseText
    }

    if (params.title) {
      // Discard message
      Cookies.set('alert__primary', params.title, { expires: 7, path: '/' })
    }
    if (params.location) location.href = params.location
    // location.href = (xhr.status === 201) ? $_ : Core.nextUrl($_)
  }

  static fail(xhr, status, error) {
    console.log('[fail]', xhr, status, error)
    Core.loading.stop()

    let params = { type: 'error', title: error }

    if (status === 'parsererror') {
      //
    } else if (xhr.responseJSON) {
      params = Object.assign(params, xhr.responseJSON)
    } else if (xhr.responseText) {
      params.title = xhr.responseText
    }
    Core.modal.alert(params)
  }
}
