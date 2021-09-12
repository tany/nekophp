'use strict'

global.Core = require('./javascripts/Core.js')

// Bootstrap - Tooltips
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
  return new bootstrap.Tooltip(el)
})

// Disable enter key submittion
$('form[type="POST"]').on('keypress', function(ev) {
  if (ev.key === 'Enter') ev.preventDefault()
})

// Link inherits the Parameters
$('.js-next-link').each(function() {
  if (this.hasAttribute('href')) this.setAttribute('href', Core.nextUrl(this.getAttribute('href')))
  else if (this.hasAttribute('data-href')) this.dataset.href = Core.nextUrl(this.dataset.href)
})

// Nav - activate
$('.nav-link').each(function() {
  let href = this.getAttribute('href')
  let check = (href === '/') ? location.pathname === '/' : location.pathname.indexOf(href) === 0
  this.classList.toggle('active', check)
})

// Row - link
$('.row-link').each(function() {
  let href = this.dataset.href || this.querySelector('a:first-child')?.getAttribute('href')
  if (!href) return
  $(this).css('cursor', 'pointer').on('click', function(ev) {
    if (ev.target.tagName === 'A' || ev.target.closest('a,.row-link-disabled')) return
    if (window.getSelection().isCollapsed) window.location = href
  })
})

// List - select
$('.js-list-select').each(function() {
  Core.restish.setListSelect($(this))
})

// List - deselect
$('.js-list-deselect').each(function() {
  Core.restish.setListDeselect($(this))
})

// List - delete
$('.js-list-delete').each(function() {
  Core.restish.setListDelete($(this))
})

// REST - create
$('.js-rest-create').each(function() {
  Core.restish.setCreate($(this))
})

// REST - update
$('.js-rest-update').each(function() {
  Core.restish.setUpdate($(this))
})

// REST - delete
$('.js-rest-delete').each(function() {
  Core.restish.setDelete($(this))
})

// Form
$('.js-form-submit').each(function() {
  Core.restish.setAjaxSubmit($(this))
})

// Clipboard - copy
$('.js-clipboard-copy').each(function() {
  Core.clipboard.setCopy($(this))
})

// $('.js-clipboard-copy').on('click', function(ev) {
//   let el = ev.currentTarget
//   let icon = $(el.querySelector('.bi'))

//   Core.clipboard.copy(el.nextElementSibling)
//   icon.toggleClass('bi-clipboard bi-check2')
//   setTimeout(() => icon.toggleClass('bi-clipboard bi-check2'), 1000)
//   ev.preventDefault()
// })
