export default class Form {

  // TODO:
  static disableAjaxSubmit(el) {
    el.classList.add('disabled');
    $('.js-rest-create, .js-rest-update').off('click');
  }

  static disableEnter(el) {
    el.addEventListener('keypress', ev => {
      if (ev.key === 'Enter' && ev.target.tagName !== 'TEXTAREA') ev.preventDefault();
    });
  }

  static disableButton(el) {
    el.classList.add('disabled');
    setTimeout(e => e.classList.remove('disabled'), 2000, el);
  }

  static focusField(el) {
    if (!el || !el.value) return;
    el.focus();
    el.setSelectionRange(el.value.length, el.value.length);
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
