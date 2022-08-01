// Slider Price :
import nouislider from "nouislider";
import 'nouislider/dist/nouislider.min.css';

var slider = document.getElementById('price-slider');

if (slider){
    const min = document.getElementById('min');
    const max = document.getElementById('max');
    const range = nouislider.create(slider, {
        start: [min.value || parseInt(slider.dataset.min) ,max.value || parseInt(slider.dataset.max)],
        connect: true,
        range: {
            'min': parseInt(slider.dataset.min, 10),
            'max': parseInt(slider.dataset.max, 10)
        }
    });

    range.on('slide', function (values,handle) {
        if(handle === 0){
            min.value = Math.round(values[0])
        }
        if(handle === 1){
            max.value = Math.round(values[1])
        }
    })
}

// const price_slider_color = document.querySelector(".noUi-connect");
const handle = document.querySelector(".noUi-handle");
const aria_value = handle.getAttribute("aria-valuenow");
console.log(aria_value);


// let psc_style = price_slider_color.style;
// const transform = price_slider_color.style.transform;
// console.log(transform);



// if (transform != 'translate(0%, 0px) scale(1, 1)') {
//     price_slider_color.classList.add("change_color");
// };

// let handle = document.querySelector(".noUi-handle");

// handle.addEventListener("click", function (e) {
//     e.preventDefault();
//     price_slider_color.classList.add("change_color");
//   price_slider_color.classList.toggle("change_color");
// });

// Filtres en Ajax :

import Filter from '../../modules/Filter';

// new Filter(document.querySelector('.js-filter'))