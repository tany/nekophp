import ClassList from './javascripts/ClassList';
import Clipboard from './javascripts/Clipboard';
import Form from './javascripts/Form';
import Link from './javascripts/Link';
import Loading from './javascripts/Loading';
import Modal from './javascripts/Modal';
import Response from './javascripts/Response';
import RESTish from './javascripts/RESTish';
import Utils from './javascripts/Utils';

window.core = {
  ClassList,
  Clipboard,
  Form,
  Link,
  Loading,
  Modal,
  RESTish,
  Response,
  Utils,
};

// Bootstrap - tooltip
[...document.querySelectorAll('[data-bs-toggle="tooltip"]')].forEach(el => {
  new bootstrap.Tooltip(el);
});

// TODO: Bootstrp - dropdown
document.querySelectorAll('.dropdown-switch').forEach(el => {
  el.addEventListener('click', ev => {
    el.closest('ul').querySelectorAll('li').forEach(li => {
      li.classList.toggle('d-none');
    });
    ev.preventDefault();
    ev.stopPropagation();
  });
});

// Disable enter key submittion
document.querySelectorAll('form[type="POST"]').forEach(el => {
  el.addEventListener('keypress', ev => {
    if (ev.key === 'Enter') ev.preventDefault();
  });
});

// Link inherits the Parameters
document.querySelectorAll('.js-next-link, [data-next-href]').forEach(el => {
  core.Link.setNextLink(el);
});

// Ajax link
document.querySelectorAll('.js-ajax-link').forEach(el => {
  core.Link.setAjaxLink(el);
});

// Row link
document.querySelectorAll('.js-row-link').forEach(el => {
  core.Link.setRowLink(el);
});

// Nav link
document.querySelectorAll('.js-nav-link').forEach(el => {
  core.Link.setNavLink(el);
});

// List - select
document.querySelectorAll('.js-list-select').forEach(el => {
  core.RESTish.setListSelect(el);
});

// List - deselect
document.querySelectorAll('.js-list-deselect').forEach(el => {
  core.RESTish.setListDeselect(el);
});

// List - delete
document.querySelectorAll('.js-list-delete').forEach(el => {
  core.RESTish.setListDelete(el);
});

// REST - create
document.querySelectorAll('.js-rest-create').forEach(el => {
  core.RESTish.setCreate(el);
});

// REST - update
document.querySelectorAll('.js-rest-update').forEach(el => {
  core.RESTish.setUpdate(el);
});

// REST - delete
document.querySelectorAll('.js-rest-delete').forEach(el => {
  core.RESTish.setDelete(el);
});

// Form
document.querySelectorAll('.js-form-submit').forEach(el => {
  core.Form.setAjaxSubmit(el);
});

// Clipboard - copy
document.querySelectorAll('.js-clipboard-copy').forEach(el => {
  core.Clipboard.setCopy(el);
});
