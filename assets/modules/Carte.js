///////////////////////////////////
//// Carte page publique vendeur
//////////////////////////////////

    // function oneBoutiqueCard()
    // {
        var carte_box = document.getElementById('maCarte')
        var city = carte_box.getAttribute('city');
        var lon = carte_box.getAttribute('data-lon');
        var lat = carte_box.getAttribute('data-lat');

        // On initialise la carte
        var carte = L.map(carte_box).setView([lat, lon], 14);

        // On ajoute une couche (tiles)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(carte);

        var marqueur = L.marker([parseFloat(lat), parseFloat(lon)]).addTo(carte);
        marqueur.bindPopup("<b>"+city+"</b>");

    // }

    // oneBoutiqueCard()



//////////////////////////////////////////////
//// Carte homepage avec toutes les boutiques
/////////////////////////////////////////////

// var villes = {
//     "Paris": {"lat": 48.752969 , "lon": 2.349903},
//     "Pont-Audemer": {"lat": 49.349998 , "lon": 0.51667},
//     "Brest": {"lat": 48.383 , "lon": -4.500},
//     "Quimper": {"lat": 48.000 , "lon": -4.100},
//     "Bayonne": {"lat": 43.500 , "lon": -1.467},
// }
// On initialise la carte
// var carte = L.map(carte_box).setView([lattitude, lon ], 13);

// On ajoute une couche (tiles)
// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     maxZoom: 19,
//     attribution: '© OpenStreetMap'
// }).addTo(carte);

// On créé un cluster (groupe de marqueur lorsqu'ils sont proches)
// var marqueurs = L.markerClusterGroup();

// On personnalise notre icone
// var icone = L.icon({
//     iconUrl : "images/marqueur-de-carte.png",
//     iconSize : [40, 50],
//     iconAnchor: [20,15],
//     popupAnchor : [0,-50]
// })

// for (ville in villes){
// var marqueur = L.marker([parseFloat(lattitude),parseFloat(lon)]);
    // , {icon: icone})
//     marqueur.bindPopup("<b>"+city+"</b>")
//     marqueurs.addLayer(marqueur)
// }

// carte.addLayer(marqueurs)

