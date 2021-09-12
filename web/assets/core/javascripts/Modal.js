'use strict'

module.exports = class Modal {

  static id = 'core-modal-alert'

  static alert(params) {
    $(`#${Modal.id}`).remove()

    let html = $(Modal._getTemplate(Object.assign({
      header: params.title,
      body: params.message,
      footer: `<button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">OK</button>`,
    }, params)))

    let modal = new bootstrap.Modal(html[0])
    modal.show()
  }

  static confirm(params) {
    $(`#${Modal.id}`).remove()

    let html = $(Modal._getTemplate(Object.assign({
      header: params.title,
      body: params.message,
      footer: `
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-sm btn-primary">OK</button>
      `,
    }, params)))

    let modal = new bootstrap.Modal(html[0])

    return new Promise((resolve, reject) => {
      modal.show()
      html.find('.btn-primary').on('click', () => resolve(1))
      html.on('hide.bs.modal', () => reject(2))
    })
  }

  static _getTemplate(params = []) {
    if (params.body) params.body = Modal._convertBody(params.body)

    return `
      <div id="${Modal.id}" class="modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content type-${params.type}">
        <div class="modal-header">${params.header ?? ''}</div>
        <div class="modal-body">${params.body ?? ''}</div>
        <div class="modal-footer">${params.footer ?? ''}</div>
      </div></div></div>
    `
  }

  static _convertBody(data, html = '') {
    if (Array.isArray(data)) {
      html += '<ul>'
      for (let item of data) html = this._convertAlertBody(item, html)
      html += '</ul>'
    } else if (html) {
      html += '<li>' + String(data).replace(/\r?\n/g, '<br>') + '</li>'
    } else {
      html += '<div>' + String(data).replace(/\r?\n/g, '<br>') + '</div>'
    }
    return html
  }
}
