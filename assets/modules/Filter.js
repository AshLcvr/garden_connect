/**
 * @property {HTMLElement} pagination
 * @property {HTMLElement} content
 * @property {HTMLElement} sort
 * @property {HTMLFormElement} form
 */

export default class Filter {
    constructor(element)
    {
        /**
         *  @param {HTMLElement|null} element
         */
        if (element === null){
            return
        }
        this.pagination = element.querySelector('.js-filter-pagination');
        this.content    = element.querySelector('.js-filter-content');
        this.sort       = element.querySelector('.js-filter-sort');
        this.form       = element.querySelector('.js-filter-form');
        this.bindEvent()
    }

    bindEvent(){
        this.sort.addEventListener('click', e =>{
            if (e.target.tagName === 'A'){
                e.preventDefault();
                this.loadUrl(e.target.getAttribute('href'))
            }
        });

        this.form.querySelectorAll('.annonce_category').forEach(input => {
            input.addEventListener('change', this.loadForm.bind(this))
        })
    }

    async loadForm(){
        const data = new FormData(this.form);
        const url = new URL(this.form.getAttribute('action') || window.location.href)
        const params = new URLSearchParams();
        data.forEach((value, key) =>
            params.append(key,value)
        );
        return this.loadUrl(url.pathname + '?' + params.toString())
    }

    async loadUrl(url){
        const response = await fetch (url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        try{
            const data             = await response.json();
            this.content.innerHTML = data.content;
            this.sort.innerHTML    = data.sort;
            history.replaceState({},'',url)
        }catch {
            console.log(response)
        }
    }
}

