export default class ClassList {

  static toggle(el, token, force = null) {
    if (force === null) {
      token.split(' ').forEach(v => el.classList.toggle(v));
    } else {
      token.split(' ').forEach(v => el.classList.toggle(v, force));
    }
    return this;
  }
}
