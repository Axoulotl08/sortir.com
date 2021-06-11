let ville = document.querySelector('#campusOrganisateur').innerText
nominatimTest(ville)

function carte(){
    let latitudeCarte = document.querySelector('#latitude').innerText;
    let longitudeCarte = document.querySelector('#longitude').innerText;

    let nomRue = document.querySelector('#rue').innerText;
    console.log(latitudeCarte + ";" +longitudeCarte)
    if(latitudeCarte == ""){
        latitudeCarte = latCampus;
        longitudeCarte = longCampus;
    }

    var carteOsM = L.map('carteInfo').setView([latitudeCarte, longitudeCarte], 13);

    L.tileLayer('https://tiles.stadiamaps.com/tiles/osm_bright/{z}/{x}/{y}{r}.png', {
        // Il est toujours bien de laisser le lien vers la source des données
        attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a>, &copy; AMichel-NBeurel contributors',
        minZoom: 1,
        maxZoom: 20,
        name: 'maCarte'
    }).addTo(carteOsM);

    var centrage = L.marker([latitudeCarte, longitudeCarte]).addTo(carteOsM);
    centrage.bindPopup('<span class=\"ruePopup\"> Rue: ' + nomRue + '</span><br> coordonées gps: <br>' + latitudeCarte +", " + longitudeCarte ).openPopup();

    carteOsM.on('click', function(e) {
        console.log(e)
    });

}

function nominatimTest(ville){

    fetch('https://nominatim.openstreetmap.org/search?city='+ ville+'&format=json&polygon_geojson=1&addressdetails=1').then(response=>{
        return response.json();
    }).then(test=>{
        latCampus = test[0].lat
        longCampus = test[0].lon
        console.log(latCampus + "; " + longCampus)
        carte()
    })
}


document.querySelector('#sortie_ville').addEventListener('change',(e)=>{
    ville = e.target.options[e.target.selectedIndex].text;
    document.querySelector('#blockCarte').innerHTML = '<div id="carteInfo"></div>';
    nominatimTest(ville);
})

document.querySelector('#sortie_lieu').addEventListener('change',(e)=>{
    document.querySelector('#blockCarte').innerHTML = '<div id="carteInfo"></div>';
    setTimeout(function(){
        nominatimTest(ville);
    }, 200);

})

