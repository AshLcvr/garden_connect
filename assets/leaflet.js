///////////////////////////////////
//// Carte page publique vendeur
//////////////////////////////////

// function oneBoutiqueCard()
// {
const carte_box      = document.getElementById('boutique_coordinates')
const boutique_title = carte_box.getAttribute('data-boutique_title');
const fullAdress     = carte_box.getAttribute('data-full');
const lng            = carte_box.getAttribute('data-lng');
const lat            = carte_box.getAttribute('data-lat');

// On initialise la carte
const carte = L.map(carte_box).setView([lat, lng], 14);

// On ajoute une couche (tiles)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(carte);

const marqueur = L.marker([parseFloat(lat), parseFloat(lng)]).addTo(carte);
if (fullAdress === 'true'){
    marqueur.bindPopup("<b>"+boutique_title+"</b>");
}else{
    marqueur.bindPopup("<b>"+boutique_title+"</b><br><i>Position approximative</i>");
}


// }

//////////////////////////////////////////////
//// Carte homepage avec toutes les boutiques
/////////////////////////////////////////////

// const villes = {
//     "Paris": {"lat": 48.752969 , "lng": 2.349903},
//     "Pont-Audemer": {"lat": 49.349998 , "lng": 0.51667},
//     "Brest": {"lat": 48.383 , "lng": -4.500},
//     "Quimper": {"lat": 48.000 , "lng": -4.100},
//     "Bayonne": {"lat": 43.500 , "lng": -1.467},
// }
// On initialise la carte
// const carte = L.map(carte_box).setView([lattitude, lng ], 13);

// On ajoute une couche (tiles)
// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     maxZoom: 19,
//     attribution: '© OpenStreetMap'
// }).addTo(carte);

// On créé un cluster (groupe de marqueur lorsqu'ils sont proches)
// const marqueurs = L.markerClusterGroup();

// On personnalise notre icone
// const icone = L.icon({
//     iconUrl : "images/marqueur-de-carte.png",
//     iconSize : [40, 50],
//     iconAnchor: [20,15],
//     popupAnchor : [0,-50]
// })

// for (ville in villes){
// const marqueur = L.marker([parseFloat(lattitude),parseFloat(lng)]);
// , {icon: icone})
//     marqueur.bindPopup("<b>"+city+"</b>")
//     marqueurs.addLayer(marqueur)
// }

// carte.addLayer(marqueurs)

