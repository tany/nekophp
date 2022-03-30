export default class DataList {

  static setSelect(el) {
    const root = el.closest('table');
    const name = root.querySelector('tbody input[type="checkbox"]')?.getAttribute('name');

    el.addEventListener('click', ev => {
      root.querySelectorAll(`input[name="${name}"]:first-child`).forEach(chk => {
        chk.checked = el.checked;
      });
    });
  }

  static setDelete(el) {
    const root = el.closest('table');
    const href = el.dataset.href ?? '.';
    const name = root.querySelector('tbody input[type="checkbox"]')?.getAttribute('name');

    el.addEventListener('click', ev => {
      const ids = Array.from(root.querySelectorAll(`input[name="${name}"]:first-child:checked`)).map(chk => chk.value);
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
}
