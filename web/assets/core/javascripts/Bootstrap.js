export default class Bootstrap {

  static setDropdownSwitch(el) {
    el.addEventListener('click', ev => {
      el.closest('ul').querySelectorAll('li').forEach(li => {
        li.classList.toggle('d-none');
      });
      ev.preventDefault();
      ev.stopPropagation();
    });
  }
}
