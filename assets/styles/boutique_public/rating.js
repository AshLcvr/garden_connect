console.log('coucou')

const ratings = document.querySelectorAll('.rating');
ratings.forEach(rating =>
    rating.addEventListener('mouseleave', ratingHandler)
);

const stars = document.querySelectorAll('.rating .star');
stars.forEach(star => {
    star.addEventListener('mouseover', starSelection);
    star.addEventListener('mouseleave', starSelection);
    star.addEventListener('click', activeSelect);
});

function ratingHandler(e) {
    const childStars = e.target.children;
    for(let i = 0; i < childStars.length; i++) {
        const star = childStars.item(i)
        if (star.dataset.checked === "true") {
            star.classList.add('hover');
        }
        else {
            star.classList.remove('hover');
        }
    }
}

function starSelection(e) {
    const parent = e.target.parentElement
    const childStars = parent.children;
    const dataset = e.target.dataset;
    const note = +dataset.note; // Convert note (string) to note (number)
    for (let i = 0; i < childStars.length; i++) {
        const star = childStars.item(i)
        if (+star.dataset.note > note) {
            star.classList.remove('hover');
        } else {
            star.classList.add('hover');
        }
    }
}

function activeSelect(e) {
    const parent = e.target.parentElement
    const childStars = parent.children;
    const dataset = e.target.dataset;
    const note = +dataset.note; // Convert note (string) to note (number)
    for (let i = 0; i < childStars.length; i++) {
        const star = childStars.item(i)
        if (+star.dataset.note > note) {
            star.classList.remove('hover');
            star.dataset.checked = "false";
        } else {
            star.classList.add('hover');
            star.dataset.checked = "true";
        }
    }
    document.querySelector('input[name="avis_form[rating]"]').value = note;
}
