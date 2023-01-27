import {Flipper, spring} from 'flip-toolkit';

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
        this.pagination.addEventListener('click', e => {
            if (e.target.tagName === 'A') {
                e.preventDefault()
                this.loadUrl(e.target.getAttribute('href'))
            }
        })
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
        return this.loadUrl(url.pathname + '?' + params.toString())

    }

    async loadUrl (url) {
        this.showLoader()
        const ajaxUrl = url + '&ajax=1'
        const response = await fetch(ajaxUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        if (response.status >= 200 && response.status < 300) {
            const data = await response.json()
            this.flipContent(data.content)
            this.pagination.innerHTML = data.pagination
            this.bindEvents()
        } else {
            console.error(response)
        }
        this.hideLoader()
    }


    /**
     *
     * @param {string} content 
     */
    flipContent(content) {
        // Flip spring
        const exitSpring = function (element, index, onComplete) {
            spring({
                config: "wobbly",
                values: {
                    translateY: [0, -20],
                    opacity: [1, 0]
                },
                onUpdate: ({ translateY, opacity }) => {
                    element.style.opacity = opacity;
                    element.style.transform = `translateY(${translateY}px)`;
                },
                onComplete
            });
        }
        const appearSpring = function (element, index) {
            spring({
                config: "wobbly",
                values: {
                    translateY: [20, 0],
                    opacity: [0, 1]
                },
                onUpdate: ({ translateY, opacity }) => {
                    element.style.opacity = opacity;
                    element.style.transform = `translateY(${translateY}px)`;
                },
                delay: index * 20
            });
        }

        // new Flipper's instance
        const flipper = new Flipper({
            element: this.content,
        })

        // exit animation
        Array.from(this.content.children).forEach((element) => {
            flipper.addFlipped({
                element,
                flipId: element.id,
                shouldFlip: false,
                onExit: exitSpring,
            });
        });

        // update the DOM
        flipper.recordBeforeUpdate()
        this.content.innerHTML = content

        // Appearance animation
        Array.from(this.content.children).forEach((element) => {
            flipper.addFlipped({
                element,
                flipId: element.id,
                onAppear: appearSpring,
            });
        });

        // update the DOM
        flipper.update()
    }

    showLoader() {
        this.form.classList.add('is-loading')
        const loader = this.form.querySelector('.home-js-loading')
        if (loader === null) {
            return
        }
        loader.setAttribute('aria-hidden', 'false')
        loader.style.display = null
    }

    hideLoader() {
        this.form.classList.remove('is-loading')
        const loader = this.form.querySelector('.home-js-loading')
        if (loader === null) {
            return
        }
        loader.setAttribute('aria-hidden', 'true')
        loader.style.display = 'none'
    }
}