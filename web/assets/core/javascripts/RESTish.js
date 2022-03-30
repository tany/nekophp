export default class RESTish {

  static setCreate(el) {
    const form = el.closest('form');
    const href = form.getAttribute('action') ?? '.';

    el.addEventListener('click', ev => {
      $(form).ajaxSubmit({ dataType: 'json', url: href }).data('jqxhr')
        .then(core.Response.done)
        .catch(core.Response.fail);
      ev.preventDefault();
      core.Form.disableButton(el);
    });
    // window.stop(); // for LiveReload
  }

  static setUpdate(el) {
    const form = el.closest('form');
    const href = form.getAttribute('action') ?? '?';

    el.addEventListener('click', ev => {
      $(form).ajaxSubmit({ data: { _method: 'PUT' }, dataType: 'json', type: 'POST', url: href }).data('jqxhr')
        .then(core.Response.done)
        .catch(core.Response.fail);
      ev.preventDefault();
      core.Form.disableButton(el);
    });
    // window.stop(); // for LiveReload
  }

  static setDelete(el) {
    const href = el.getAttribute('href') ?? el.dataset.href ?? '?';

    el.addEventListener('click', ev => {
      core.Modal.confirm({ message: el.dataset.confirm }).then(() => {
        $.ajax({ data: { _method: 'DELETE' }, dataType: 'json', type: 'POST', url: href })
          .then(core.Response.done)
          .catch(core.Response.fail);
        core.Form.disableButton(el);
      }).catch(() => {});
      ev.preventDefault();
      core.Form.disableButton(el);
    });
  }
}
