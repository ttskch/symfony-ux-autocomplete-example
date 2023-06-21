// @see https://symfony.com/bundles/ux-autocomplete/current/index.html#extending-tom-select

import {Controller} from '@hotwired/stimulus'

export default class extends Controller {
  initialize() {
    this._onPreConnect = this._onPreConnect.bind(this);
    this._onConnect = this._onConnect.bind(this);
  }

  connect() {
    this.element.addEventListener('autocomplete:pre-connect', this._onPreConnect);
    this.element.addEventListener('autocomplete:connect', this._onConnect);
  }

  disconnect() {
    // You should always remove listeners when the controller is disconnected to avoid side-effects
    this.element.removeEventListener('autocomplete:pre-connect', this._onConnect);
    this.element.removeEventListener('autocomplete:connect', this._onPreConnect);
  }

  _onPreConnect(event) {
    // TomSelect has not been initialized - options can be changed
    // console.log(event.detail.options); // Options that will be used to initialize TomSelect
    // event.detail.options.onChange = (value) => {
    //   // ...
    // };

    // @see https://tom-select.js.org/docs/
    // @see https://tom-select.js.org/plugins/virtual_scroll/

    event.detail.options.maxOptions = undefined

    event.detail.options.closeAfterSelect =
      !event.srcElement.hasAttribute('multiple')

    event.detail.options.shouldLoad = (query) => true

    event.detail.options.render.no_results = () =>
      `<div class="no-results text-muted">Not found</div>`

    event.detail.options.render.loading = () => `
      <div class="ps-3 py-1">
        <div class="spinner-border text-muted spinner-border-sm" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    `

    event.detail.options.render.loading_more = () => `
      <div class="loading-more-results ps-3 py-1">
        <div class="spinner-border text-muted spinner-border-sm" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    `

    event.detail.options.render.no_more_results = () =>
      `<div class="no-more-results text-muted">No more results</div>`

    if (event.srcElement.hasAttribute('multiple')) {
      event.detail.options.plugins.remove_button = {}
      event.detail.options.plugins.clear_button = {}
    } else {
      delete event.detail.options.plugins.clear_button
    }
  }

  _onConnect(event) {
    // TomSelect has just been intialized and you can access details from the event
    // console.log(event.detail.tomSelect); // TomSelect instance
    // console.log(event.detail.options); // Options used to initialize TomSelect
  }
}
