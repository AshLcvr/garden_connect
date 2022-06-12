/**
 * @property {HTMLElement} pagination
 * @property {HTMLElement} content
 * @property {HTMLElement} sort
 * @property {HTMLElement} form
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
        this.pagination = element.querySelector('.js-filter-pagination')
        this.content = element.querySelector('.js-filter-content')
        this.sort = element.querySelector('.js-filter-sort')
        this.form = element.querySelector('.js-filter-form')
        this.bindEvents()
    }

    bindEvent(){
        this.sort.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', e =>{
                e.preventDefault()
                this.loadUrl( a.getAttribute('href'))
            })
        })
    }

    loadUrl(url){
        const  response =  fetch (url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })

        if (response >= 200 && response.status > 300){
            const data =  response.json()
            this.content.innerHTML = data.content
        }else{
            console.log(response)
        }
    }
}

