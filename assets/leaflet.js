///////////////////////////////////
//// Carte page publique vendeur
//////////////////////////////////
const carte_box      = document.getElementById('boutique_coordinates');
const boutique_title = carte_box.getAttribute('data-boutique_title');
const fullAdress     = carte_box.getAttribute('data-full');
const lng            = carte_box.getAttribute('data-lng');
const lat            = carte_box.getAttribute('data-lat');
const carte = L.map(carte_box).setView([lat, lng], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(carte);

const icone = L.icon({
    iconUrl : '../../build/images/icone_boutique.png',
    iconSize : [50, 50],
    iconAnchor: [20,15],
    popupAnchor : [4,-12]
})
const marqueur = L.marker( [ parseFloat(lat), parseFloat(lng) ], {icon:icone} ).addTo(carte);
fullAdress === 'true'? marqueur.bindPopup("<b>"+boutique_title+"</b>") : marqueur.bindPopup("<b>"+boutique_title+"</b><br><i>Position approximative</i>");