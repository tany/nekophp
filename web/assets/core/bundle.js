import Bootstrap from './javascripts/Bootstrap';
import ClassList from './javascripts/ClassList';
import Clipboard from './javascripts/Clipboard';
import DataList from './javascripts/DataList';
import Form from './javascripts/Form';
import Link from './javascripts/Link';
import Loading from './javascripts/Loading';
import Modal from './javascripts/Modal';
import Response from './javascripts/Response';
import RESTish from './javascripts/RESTish';
import Utils from './javascripts/Utils';

window.core = {
  Bootstrap,
  ClassList,
  Clipboard,
  DataList,
  Form,
  Link,
  Loading,
  Modal,
  RESTish,
  Response,
  Utils,
};

// Bootstrap - Tooltip
[...document.querySelectorAll('[data-bs-toggle="tooltip"]')].forEach(el => {
  new bootstrap.Tooltip(el);
});

// Bootstrap - Dropdown switch
document.querySelectorAll('.dropdown-switch').forEach(el => {
  core.Bootstrap.setDropdownSwitch(el)
});

// Link - Inherits the Parameters
document.querySelectorAll('.js-link-next').forEach(el => {
  core.Link.setNextLink(el);
});

// Link - Nav
document.querySelectorAll('.js-link-nav').forEach(el => {
  core.Link.setNavLink(el);
});

// Link - Row
document.querySelectorAll('.js-link-row').forEach(el => {
  core.Link.setRowLink(el);
});

// Link - Ajax
document.querySelectorAll('.js-link-ajax').forEach(el => {
  core.Link.setAjaxLink(el);
});

// List - Select
document.querySelectorAll('.js-list-select').forEach(el => {
  core.DataList.setSelect(el);
});

// List - Delete
document.querySelectorAll('.js-list-delete').forEach(el => {
  core.DataList.setDelete(el);
});

// Form - Disable enter key submittion
document.querySelectorAll('form[method="POST"]').forEach(el => {
  core.Form.disableEnter(el);
});

// Form - Ajax Submit
document.querySelectorAll('.js-ajax-submit').forEach(el => {
  core.Form.setAjaxSubmit(el);
});

// REST - Create
document.querySelectorAll('.js-rest-create').forEach(el => {
  core.RESTish.setCreate(el);
});

// REST - Update
document.querySelectorAll('.js-rest-update').forEach(el => {
  core.RESTish.setUpdate(el);
});

// REST - Delete
document.querySelectorAll('.js-rest-delete').forEach(el => {
  core.RESTish.setDelete(el);
});

// Clipboard - Copy
document.querySelectorAll('.js-clipboard-copy').forEach(el => {
  core.Clipboard.setCopy(el);
});
