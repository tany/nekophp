export default class Response {

  static done(data, status, xhr) {
    console.debug(`[${status}]`, data);
    core.Loading.stop();

    const params = xhr.responseJSON ?? {};

    if (params.reflesh) {
      params.location = window.location.href;
    }

    if (params.results) {
      Response.modal(params).then(() => {
        if (params.location) Response.redirect(params);
      });
    } else if (params.location) {
      Response.redirect(params);
    }
  }

  static modal(params) {
    return core.Modal.alert({
      class: 'modal-lg modal-type-code',
      body: JSON.stringify(params.results, null, 2),
    });
  }

  static redirect(params) {
    if (params.alert) {
      Cookies.set('alert__primary', params.alert, { expires: 1, path: '/' });
    }
    window.location = params.location;
  }

  static fail(xhr, status, error) {
    console.debug(`[${status}]`, error, xhr);
    core.Loading.stop();

    let params = {
      class: 'modal-type-error',
      title: error,
    };

    if (status === 'parsererror') {
      //
    } else if (xhr.responseJSON) {
      params = Object.assign(params, xhr.responseJSON);
    } else if (xhr.responseText) {
      params.title = xhr.responseText;
    }

    core.Modal.alert(params);
  }
}
