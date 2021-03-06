export default class Loading {

  static id = 'core-loading';

  static start() {
    $('body').append(`
      <div class="d-flex-center ${Loading.id}">
      <div class="spinner-border text-secondary" role="status">
      <span class="visually-hidden">Loading...</span></div></div>
    `);
  }

  static stop() {
    $(`.${Loading.id}`).remove();
  }
}
