export default class Form {

  static disableButton(el) {
    el.classList.add('disabled');
    setTimeout(e => e.classList.remove('disabled'), 2000, el);
  }

  // TODO:
  static disableAjaxSubmit(el) {
    el.classList.add('disabled');
    $('.js-rest-create, .js-rest-update').off('click');
  }

  static focusField(selector) {
    const q = document.querySelector(selector);
    if (!q?.value?.length) return;
    q.focus();
    q.setSelectionRange(q.value.length, q.value.length);
  }

  static setAjaxSubmit(el) {
    const form = el.closest('form');
    const href = form.getAttribute('action') ?? '?';

    el.addEventListener('click', ev => {
      $(form).ajaxSubmit({ dataType: 'json', url: href }).data('jqxhr')
        .then(core.Response.done)
        .catch(core.Response.fail);
      ev.preventDefault();
      core.Form.disableButton(el);
    });
  }
}
