export default class Modal {

  static id = 'core-modal';

  static alert(params) {
    if ($_ = document.querySelector(`#${Modal.id}`)) $_.remove();

    const data = {
      header: params.title,
      body: params.message,
      footer: '<button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">OK</button>',
    };
    const html = Modal.getTemplate(Object.assign(data, params));
    const modal = new bootstrap.Modal(html[0]);

    return new Promise(resolve => {
      modal.show();
      html.on('hide.bs.modal', () => resolve());
    });
  }

  static confirm(params) {
    if ($_ = document.querySelector(`#${Modal.id}`)) $_.remove();

    const data = {
      header: params.title,
      body: params.message,
      footer: `
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-sm btn-primary">OK</button>
      `,
    };
    const html = Modal.getTemplate(Object.assign(data, params));
    const modal = new bootstrap.Modal(html[0]);

    return new Promise((resolve, reject) => {
      modal.show();
      html.find('.btn-primary').on('click', () => resolve());
      html.on('hide.bs.modal', () => reject());
    });
  }

  static getTemplate(params = {}) {
    if (params.body) params.body = Modal.convertBody(params.body);

    return $(`
      <div id="${Modal.id}" class="modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ${params.class}">
      <div class="modal-content">
        <div class="modal-header">${params.header ?? ''}</div>
        <div class="modal-body">${params.body ?? ''}</div>
        <div class="modal-footer">${params.footer ?? ''}</div>
      </div></div></div>
    `);
  }

  static convertBody(data, html = '') {
    if (Array.isArray(data)) {
      html += '<ul>';
      data.forEach(item => {
        html = this.convertAlertBody(item, html);
      });
      html += '</ul>';
      return html;
    }
    data = String(data).replace(/\r?\n/g, '<br>');
    html += html ? `<li>${data}</li>` : `<div>${data}</div>`;
    return html;
  }
}
