export default class Link {

  static nextUrl(url = '') {
    let [path, query] = url.split('?');

    if (path === '') path = window.location.pathname;
    query ??= '';

    let params = window.location.search.replace('?', '').replace(/^_\w+(&|$)/, '$1');
    params = [...new URLSearchParams(`${query}&${params}`).entries()];
    params = params.reduce((obj, e) => ({ ...obj, [e[0]]: e[1] }), {});
    query = Object.entries(params).map(e => `${e[0]}=${e[1]}`).join('&').replace(/=(&|$)/, '$1');
    return query ? `${path}?${query}` : path;
  }

  static setNextLink(el) {
    if (el.hasAttribute('href')) {
      el.setAttribute('href', Link.nextUrl(el.getAttribute('href')));
    }
    if (el.hasAttribute('data-href')) {
      el.dataset.href = Link.nextUrl(el.dataset.href);
    }
  }

  static setNavLink(el) {
    const href = el.getAttribute('href');
    const path = window.location.pathname;
    const active = (href === '/') ? path === '/' : path.indexOf(href) === 0;

    el.classList.toggle('active', active);
  }

  static setRowLink(el) {
    el.style.cursor = 'pointer';
    el.addEventListener('click', ev => {
      const href = el.dataset.href ?? el.querySelector('a[href]')?.getAttribute('href');
      if (!href) return;
      if (ev.target.tagName === 'A' || ev.target.closest('a,.unlink')) return;
      if (window.getSelection().isCollapsed) window.location = href;
    });
  }

  static setAjaxLink(el) {
    const href = el.getAttribute('href') ?? el.dataset.href ?? '?';

    el.addEventListener('click', ev => {
      core.Loading.start();
      $.ajax({ dataType: 'json', url: href })
        .then(core.Response.done)
        .catch(core.Response.fail);
      ev.preventDefault();
    });
  }
}
