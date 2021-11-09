export default class RESTish {

  static setListSelect(el) {
    const root = el.closest('table');
    const name = root.querySelector('tbody input[type="checkbox"]')?.getAttribute('name');

    el.addEventListener('click', ev => {
      root.querySelectorAll(`input[name="${name}"]`).forEach(chk => {
        chk.checked = true;
      });
      ev.preventDefault();
      ev.stopPropagation();// return false;
    });
  }

  static setListDeselect(el) {
    const root = el.closest('table');
    const name = root.querySelector('tbody input[type="checkbox"]')?.getAttribute('name');

    el.addEventListener('click', ev => {
      root.querySelectorAll(`input[name="${name}"]`).forEach(chk => {
        chk.checked = false;
      });
      ev.preventDefault();
      ev.stopPropagation();// return false;
    });
  }

  static setListDelete(el) {
    const root = el.closest('table');
    const href = el.dataset.href ?? '.';
    const name = root.querySelector('tbody input[type="checkbox"]')?.getAttribute('name');

    el.addEventListener('click', ev => {
      const ids = Array.from(root.querySelectorAll(`input[name="${name}"]:checked`)).map(chk => chk.value);
      if (ids.length === 0) {
        ev.stopPropagation();
        return;
      }
      $.ajax({ data: { _method: 'DELETE', id: ids }, dataType: 'json', type: 'POST', url: href })
        .then(core.Response.done)
        .catch(core.Response.fail);
      ev.preventDefault();
      ev.stopPropagation();
    });
  }

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
