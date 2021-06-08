

const url = new URL(window.location.origin + '/sortir.com/public');
/* todo retirer /sortir.com/public en prod */
const contenuDivForm = document.querySelector('#modal');

miseAJourDeInfoLieu();
affichageDuFormulaireNouveauLieu();
miseAjourLieuSuivantVille();

/**
 *function qui mes a jours les info du lieu séléctionner
 */
function miseAJourDeInfoLieu(){
    let selecteurLieu = document.querySelector('#sortie_lieu');
    selecteurLieu.addEventListener('change', ()=>{

        let lieuSelectionne = selecteurLieu.value

        let urlSuite = '/ajax/donneesDuLieuSelectionne';

        console.log(url.pathname + urlSuite +"?lieuSelectionne=" + lieuSelectionne);

        fetch(url.pathname + urlSuite +"?lieuSelectionne=" + lieuSelectionne).then(retour=>{
            return retour.json();
        }).then((lieu)=>{
            console.log(lieu)
            document.querySelector('#rue').innerHTML = lieu['rue'];
            document.querySelector('#codePostale').innerHTML = lieu['codePostal'];
            document.querySelector('#latitude').innerHTML = lieu['latitude'];
            document.querySelector('#longitude').innerHTML = lieu['longitude'];

        }).catch(e => alert(e));
    })
}

function miseAjourLieuSuivantVille(){
    let selecteurVille = document.querySelector('#sortie_ville');
    selecteurVille.addEventListener('change',()=>{
        let villeSelectionne = selecteurVille.value;
        let urlSuite = '/ajax/lieuDependantDeVille';
        fetch(url.pathname + urlSuite +"?idVille=" + villeSelectionne).then(reponse=>{
            return reponse.json();
        }).then(lieux=>{
            let selecteurLieu = document.querySelector('#sortie_lieu');

            selecteurLieu.innerHTML = "";
            newSelect = '<option>selectionner un lieu</option>';
            lieux.forEach((lieu) => {
                newSelect += `<option value="${lieu.id}">${lieu.nom}</option>`;
            })
            selecteurLieu.innerHTML = newSelect;

        }).catch(e=>{
            alert("il y a eut une erreur dans la recuperation des données")
        })

    })
}


/**
 * affiche le formulaire d'ajout de ville lors d'un clique sur le plus
 */
function affichageDuFormulaireNouveauLieu(){

    document.querySelector('#bouttonAjoutVille').addEventListener('click',(e)=>{
        (e.preventDefault());
        let urlFormulaireLieu = '/ajax/nouveauLieu';

        if(contenuDivForm.innerHTML == "modale"){
            fetch(url.pathname + urlFormulaireLieu, {
                headers:{
                    "X-Requested-With":"XMLHttpRequest"
                }
            }).then(reponse=>{

                return reponse.json();

            }).then(content=>{
                console.log(content)
                contenuDivForm.innerHTML = content['content'];

                traitementFormulaireLieu();
            }).catch(e=>{
                alert("il y a eut une erreur dans la recuperation du formulaire d'ajout de lieu")
            })
        }else{
            contenuDivForm.innerHTML = "modale"
        }

    })
}


/**
 * effectue la lecture du formulaire
 * ajoute un lieu dans la bdd
 * mets a jours les information lieu
 * /!\ le lieu n'est pas encore selectionnable
 */
// TODO rendre le lieu séléctionnable
function traitementFormulaireLieu() {
    const form = document.querySelector('form[name="lieu"]');
    const selectLieu = document.querySelector('#sortie_lieu')
    let urlFormulaireLieu = '/ajax/nouveauLieu';
    let urlComplet = url.pathname + urlFormulaireLieu;
    console.log(urlComplet);

    form.addEventListener('submit', (e)=> {
        e.preventDefault();
        const formulaire = e.currentTarget;
        formData = new FormData(formulaire);
        plainFormData = Object.fromEntries(formData.entries());


        const formDataJsonString = JSON.stringify(plainFormData);
        fetch(urlComplet, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formDataJsonString,
            method: "POST"
        }).then(reponse => {

            return reponse.json();

        }).then(listLieu=>{


            newSelect = '';
            listLieu.forEach((lieu, key, tableauLieu) => {
                if(key === tableauLieu.length -1){

                    //newSelect += `<option value="${lieu.id}" selected="selected">${lieu.nom}</option>`;
                    document.querySelector('#rue').innerHTML = lieu.rue;
                    document.querySelector('#codePostale').innerHTML = lieu.codePostal;
                    document.querySelector('#latitude').innerHTML = lieu.latitude;
                    document.querySelector('#longitude').innerHTML = lieu.longitude;
                    console.log(lieu.id);
                    document.querySelector('#sortie_ville').value = lieu.idVille;
                    document.querySelector('#sortie_ville').dispatchEvent(new Event('change'));
                    setTimeout(function(){document.querySelector('#sortie_lieu').value = lieu.id;},5000);
                }

                //newSelect += `<option value="${lieu.id}">${lieu.nom}</option>`;
            })
            selectLieu.innerHTML = newSelect;
            contenuDivForm.innerHTML = "modale";

        }).catch(e=>{
            alert("il y a eut une erreur dans l'execution de la demande d'ajout de lieu")
        })
    })
}
