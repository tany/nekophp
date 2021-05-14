'use strict'

global.Core = require('./javascripts/Core.js')

// Bootstrap - Tooltips
let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
let tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
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
$('.nav-link').each(function(idx, link) {
  let href = link.getAttribute('href')
  let check = (href === '/') ? location.pathname === '/' : location.pathname.indexOf(href) === 0
  link.classList.toggle('active', check)
})

// Row - link
$('.row-link').each(function() {
  let href = this.dataset.href || $(this).find('a:first').attr('href')
  if (!href) return
  $(this).css('cursor', 'pointer').on('click', function(ev) {
    if ($(ev.target).is('a') || $(ev.target).closest('a,.row-link-disabled').length) return
    if (window.getSelection().isCollapsed) window.location = href
  })
})

// List - select
$('.js-list-select').on('click', function(ev) {
  let target = this.dataset.target ?? 'id'
  $(`input[name="${target}"]`).prop('checked', true)
  ev.preventDefault()
  return false
})

// List - deselect
$('.js-list-deselect').on('click', function(ev) {
  let target = this.dataset.target ?? 'id'
  $(`input[name="${target}"]`).prop('checked', false)
  ev.preventDefault()
  return false
})

// List - delete
$('.js-list-delete').on('click', function(ev) {
  let target = this.dataset.target ?? 'id'
  let values = $(`input[name="${target}"]:checked`).map((_, el) => $(el).val()).get()
  if (values.length === 0) return false

  $.ajax({ data: { _method: 'DELETE', id: values }, dataType: 'json', type: 'POST', url: this.dataset.href ?? '.' })
    .then(Core.doneRESTish).catch(Core.failRESTish)
  ev.preventDefault()
})

// REST - create
$('.js-rest-create').on('click', function(ev) {
  let form = $(this).closest('form')
  let url = form.attr('action') ?? '.'

  form.ajaxSubmit({ dataType: 'json', type: 'POST', url: url }).data('jqxhr')
    .then(Core.doneRESTish).catch(Core.failRESTish)
  ev.preventDefault()
  Core.disableButton(this)
})

// REST - update
$('.js-rest-update').on('click', function(ev) {
  let form = $(this).closest('form')
  let url = form.attr('action') ?? '?'

  form.ajaxSubmit({ data: { _method: 'PUT' }, dataType: 'json', type: 'POST', url: url }).data('jqxhr')
    .then(Core.doneRESTish).catch(Core.failRESTish)
  ev.preventDefault()
  Core.disableButton(this)
})

// REST - delete
$('.js-rest-delete').on('click', function(ev) {
  if (!Core.confirm(this.dataset.confirm)) return false
  let url = this.getAttribute('href') ?? this.dataset.href ?? '?'

  $.ajax({ data: { _method: 'DELETE' }, dataType: 'json', type: 'POST', url: url })
    .then(Core.doneRESTish).catch(Core.failRESTish)
  ev.preventDefault()
  Core.disableButton(this)
})
