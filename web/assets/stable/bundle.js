!function(){var e={892:function(){window.elastic={}}},t={};function o(a){var s=t[a];if(void 0!==s)return s.exports;var r=t[a]={exports:{}};return e[a](r,r.exports,o),r.exports}!function(){"use strict";class e{static setCopy(t){const o=t.querySelector(".bi");t.addEventListener("click",(a=>{a.preventDefault(),e.copy(t.nextElementSibling),core.ClassList.toggle(o,"bi-clipboard bi-check2"),setTimeout((()=>core.ClassList.toggle(o,"bi-clipboard bi-check2")),1e3)}))}static copy(t){"https:"===window.location.protocol?navigator.clipboard.writeText(t.textContent):e.deprecatedCopy(t)}static deprecatedCopy(e){const t=document.getSelection(),o=new Range;o.selectNodeContents(e),t.removeAllRanges(),t.addRange(o),document.execCommand("copy"),t.removeAllRanges()}}class t{static nextUrl(e=""){let[t,o]=e.split("?");""===t&&(t=window.location.pathname),o??="";let a=window.location.search.replace("?","").replace(/^_\w+(&|$)/,"$1");return a=[...new URLSearchParams(`${o}&${a}`).entries()],a=a.reduce(((e,t)=>({...e,[t[0]]:t[1]})),{}),o=Object.entries(a).map((e=>`${e[0]}=${e[1]}`)).join("&").replace(/=(&|$)/,"$1"),o?`${t}?${o}`:t}static setNextLink(e){e.hasAttribute("href")&&e.setAttribute("href",t.nextUrl(e.getAttribute("href"))),e.hasAttribute("data-href")&&(e.dataset.href=t.nextUrl(e.dataset.href))}static setNavLink(e){const t=e.getAttribute("href"),o=window.location.pathname,a="/"===t?"/"===o:0===o.indexOf(t);e.classList.toggle("active",a)}static setRowLink(e){e.style.cursor="pointer",e.addEventListener("click",(t=>{const o=e.dataset.href??e.querySelector("a[href]")?.getAttribute("href");o&&("A"===t.target.tagName||t.target.closest("a,.unlink")||window.getSelection().isCollapsed&&(window.location=o))}))}static setAjaxLink(e){const t=e.getAttribute("href")??e.dataset.href??"?";e.addEventListener("click",(e=>{core.Loading.start(),$.ajax({dataType:"json",url:t}).then(core.Response.done).catch(core.Response.fail),e.preventDefault()}))}}class a{static id="core-loading";static start(){$("body").append(`\n      <div class="d-flex-center ${a.id}">\n      <div class="spinner-border text-secondary" role="status">\n      <span class="visually-hidden">Loading...</span></div></div>\n    `)}static stop(){$(`.${a.id}`).remove()}}class s{static id="core-modal";static alert(e){($_=document.querySelector(`#${s.id}`))&&$_.remove();const t={header:e.title,body:e.message,footer:'<button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">OK</button>'},o=s.getTemplate(Object.assign(t,e)),a=new bootstrap.Modal(o[0]);return new Promise((e=>{a.show(),o.on("hide.bs.modal",(()=>e()))}))}static confirm(e){($_=document.querySelector(`#${s.id}`))&&$_.remove();const t={header:e.title,body:e.message,footer:'\n        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>\n        <button type="button" class="btn btn-sm btn-primary">OK</button>\n      '},o=s.getTemplate(Object.assign(t,e)),a=new bootstrap.Modal(o[0]);return new Promise(((e,t)=>{a.show(),o.find(".btn-primary").on("click",(()=>e())),o.on("hide.bs.modal",(()=>t()))}))}static getTemplate(e={}){let t,o,a;return e.header&&(t=`<div class="modal-header">${e.header}</div>`),e.footer&&(o=`<div class="modal-footer">${e.footer}</div>`),e.body&&(a=`<div class="modal-body">${s.convertBody(e.body)}</div>`),$(`\n      <div id="${s.id}" class="modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">\n      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ${e.class}">\n      <div class="modal-content">${t??""}${a??""}${o??""}</div>\n      </div></div>\n    `)}static convertBody(e,t=""){return Array.isArray(e)?(t+="<ul>",e.forEach((e=>{t=this.convertAlertBody(e,t)})),t+="</ul>"):(e=String(e).replace(/\r?\n/g,"<br>"),t+=t?`<li>${e}</li>`:`<div>${e}</div>`)}}class r{static done(e,t,o){console.debug(`[${t}]`,e),core.Loading.stop();const a=o.responseJSON??{};a.reflesh&&(a.location=window.location.href),a.results?r.modal(a).then((()=>{a.location&&r.redirect(a)})):a.location&&r.redirect(a)}static modal(e){return core.Modal.alert({class:"modal-lg modal-type-code",body:JSON.stringify(e.results,null,2)})}static redirect(e){e.alert&&Cookies.set("alert__primary",e.alert,{expires:1,path:"/"}),window.location=e.location}static fail(e,t,o){console.debug(`[${t}]`,o,e),core.Loading.stop();let a={class:"modal-type-error",title:o};"parsererror"===t||(e.responseJSON?a=Object.assign(a,e.responseJSON):e.responseText&&(a.title=e.responseText)),core.Modal.alert(a)}}window.core={Bootstrap:class{static setDropdownSwitch(e){e.addEventListener("click",(t=>{e.closest("ul").querySelectorAll("li").forEach((e=>{e.classList.toggle("d-none")})),t.preventDefault(),t.stopPropagation()}))}},ClassList:class{static toggle(e,t,o=null){return null===o?t.split(" ").forEach((t=>e.classList.toggle(t))):t.split(" ").forEach((t=>e.classList.toggle(t,o))),this}},Clipboard:e,DataList:class{static setSelect(e){const t=e.closest("table"),o=t.querySelector('tbody input[type="checkbox"]')?.getAttribute("name");e.addEventListener("click",(a=>{t.querySelectorAll(`input[name="${o}"]:first-child`).forEach((t=>{t.checked=e.checked}))}))}static setDelete(e){const t=e.closest("table"),o=e.dataset.href??".",a=t.querySelector('tbody input[type="checkbox"]')?.getAttribute("name");e.addEventListener("click",(e=>{const s=Array.from(t.querySelectorAll(`input[name="${a}"]:first-child:checked`)).map((e=>e.value));0!==s.length?($.ajax({data:{_method:"DELETE",id:s},dataType:"json",type:"POST",url:o}).then(core.Response.done).catch(core.Response.fail),e.preventDefault(),e.stopPropagation()):e.stopPropagation()}))}},Form:class{static disableAjaxSubmit(e){document.querySelectorAll(".js-rest-create, .js-rest-update, .js-ajax-submit").forEach((e=>{const t=e.cloneNode(!0);e.parentNode.replaceChild(t,e)})),e.classList.add("disabled")}static disableEnter(e){e.addEventListener("keypress",(e=>{"Enter"===e.key&&"TEXTAREA"!==e.target.tagName&&e.preventDefault()}))}static disableButton(e){e.classList.add("disabled"),setTimeout((e=>e.classList.remove("disabled")),2e3,e)}static focusField(e){e&&e.value&&(e.focus(),e.setSelectionRange(e.value.length,e.value.length))}static setAjaxSubmit(e){const t=e.closest("form"),o=t.getAttribute("action")??"?";e.addEventListener("click",(a=>{$(t).ajaxSubmit({dataType:"json",url:o}).data("jqxhr").then(core.Response.done).catch(core.Response.fail),a.preventDefault(),core.Form.disableButton(e)}))}},Link:t,Loading:a,Modal:s,RESTish:class{static setCreate(e){const t=e.closest("form"),o=t.getAttribute("action")??".";e.addEventListener("click",(a=>{$(t).ajaxSubmit({dataType:"json",url:o}).data("jqxhr").then(core.Response.done).catch(core.Response.fail),a.preventDefault(),core.Form.disableButton(e)}))}static setUpdate(e){const t=e.closest("form"),o=t.getAttribute("action")??"?";e.addEventListener("click",(a=>{$(t).ajaxSubmit({data:{_method:"PUT"},dataType:"json",type:"POST",url:o}).data("jqxhr").then(core.Response.done).catch(core.Response.fail),a.preventDefault(),core.Form.disableButton(e)}))}static setDelete(e){const t=e.getAttribute("href")??e.dataset.href??"?";e.addEventListener("click",(o=>{core.Modal.confirm({message:e.dataset.confirm}).then((()=>{$.ajax({data:{_method:"DELETE"},dataType:"json",type:"POST",url:t}).then(core.Response.done).catch(core.Response.fail),core.Form.disableButton(e)})).catch((()=>{})),o.preventDefault(),core.Form.disableButton(e)}))}},Response:r,Utils:class{static benchmark(e,t=3e4){const o=Date.now();for(let o=0;o<t;o+=1)e();const a=Date.now()-o;console.debug(`${a} ms (${t} times)`)}}},[...document.querySelectorAll('[data-bs-toggle="tooltip"]')].forEach((e=>{new bootstrap.Tooltip(e)})),document.querySelectorAll(".dropdown-switch").forEach((e=>{core.Bootstrap.setDropdownSwitch(e)})),document.querySelectorAll(".js-link-next").forEach((e=>{core.Link.setNextLink(e)})),document.querySelectorAll(".js-link-nav").forEach((e=>{core.Link.setNavLink(e)})),document.querySelectorAll(".js-link-row").forEach((e=>{core.Link.setRowLink(e)})),document.querySelectorAll(".js-link-ajax").forEach((e=>{core.Link.setAjaxLink(e)})),document.querySelectorAll(".js-list-select").forEach((e=>{core.DataList.setSelect(e)})),document.querySelectorAll(".js-list-delete").forEach((e=>{core.DataList.setDelete(e)})),document.querySelectorAll('form[method="POST"]').forEach((e=>{core.Form.disableEnter(e)})),document.querySelectorAll(".js-ajax-submit").forEach((e=>{core.Form.setAjaxSubmit(e)})),document.querySelectorAll(".js-rest-create").forEach((e=>{core.RESTish.setCreate(e)})),document.querySelectorAll(".js-rest-update").forEach((e=>{core.RESTish.setUpdate(e)})),document.querySelectorAll(".js-rest-delete").forEach((e=>{core.RESTish.setDelete(e)})),document.querySelectorAll(".js-clipboard-copy").forEach((e=>{core.Clipboard.setCopy(e)})),o(892)}()}();