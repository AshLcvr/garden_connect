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

// Filtres en Ajax :

import Filter from '../../modules/Filter';

// new Filter(document.querySelector('.js-filter'))