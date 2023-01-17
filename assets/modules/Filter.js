/**
 * @property {HTMLElement} pagination
 * @property {HTMLElement} content
 * @property {HTMLFormElement} form
 */
export default class Filter {

    /**
     *
     * @param {HTMLElement|null} element
     */
    constructor(element) {
        if (element === null) {
            return
        }
        this.pagination = document.querySelector('.home-js-filter-pagination')
        this.content = document.querySelector('.home-js-filter-content')
        this.form = document.querySelector('.home-js-filter-form')
        this.bindEvents()
    }

    bindEvents() {
        this.form.querySelectorAll('input[type=checkbox]').forEach(input => {
            input.addEventListener('change', this.loadForm.bind(this))
        })
    }

    async loadForm() {
        const data = new FormData(this.form)
        const url = new URL(this.form.getAttribute('action') || window.location.href)
        const params = new URLSearchParams()
        data.forEach((value, key) => {
            params.append(key, value)
        })
        return this.loadURL(url.pathname + '?' + params.toString())

    }
}