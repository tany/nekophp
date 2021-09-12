'use strict'

module.exports = class Clipboard {

  static setCopy(el) {
    let icon = el.find('.bi')

    el.on('click', function(ev) {
      Clipboard.copy(el.next())

      icon.toggleClass('bi-clipboard bi-check2')
      setTimeout(() => icon.toggleClass('bi-clipboard bi-check2'), 1000)
      ev.preventDefault()
    })
  }

  static copy(el) {
    if (location.protocol === 'https:') {
      navigator.clipboard.writeText(el.text()) // el.textContent
    } else {
      Clipboard.deprecatedCopy(el)
    }
  }

  static deprecatedCopy(el) {
    let selection = document.getSelection()
    let range = new Range();
    range.selectNodeContents(el[0])
    selection.removeAllRanges()
    selection.addRange(range)
    document.execCommand('copy')
    selection.removeAllRanges()
  }
}
