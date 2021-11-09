export default class Clipboard {

  static setCopy(el) {
    const icon = el.querySelector('.bi');

    el.addEventListener('click', ev => {
      ev.preventDefault();
      Clipboard.copy(el.nextElementSibling);

      core.ClassList.toggle(icon, 'bi-clipboard bi-check2');
      setTimeout(() => core.ClassList.toggle(icon, 'bi-clipboard bi-check2'), 1000);
    });
  }

  static copy(el) {
    if (window.location.protocol === 'https:') {
      navigator.clipboard.writeText(el.textContent);
    } else {
      Clipboard.deprecatedCopy(el);
    }
  }

  static deprecatedCopy(el) {
    const selection = document.getSelection();
    const range = new Range();
    range.selectNodeContents(el);
    selection.removeAllRanges();
    selection.addRange(range);
    document.execCommand('copy');
    selection.removeAllRanges();
  }
}
