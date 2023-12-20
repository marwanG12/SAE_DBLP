// script.js
console.log("test");

function performSearch() {
    console.log("on y entre");

    var selectedTool = document.querySelector('input[name="tools"]:checked').value;

    // Appeler la fonction appropriée en fonction de la checkbox sélectionnée
    if (selectedTool === "author") {
        callPhpFunction('getAuthor');
    } else if (selectedTool === "affiliation") {
        callPhpFunction('getAffiliation');
    } else if (selectedTool === "keyword") {
        callPhpFunction('getTfIdf');
    }
}

function callPhpFunction(functionName) {
    // Affiche le contenu de functionName dans la console
    console.log('Function Name:', functionName);

    // Appel AJAX avec XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../Modules/ModHome/model_Home.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // Construire les données à envoyer
    var data = 'action=' + encodeURIComponent(functionName);

    // Affiche le contenu de data dans la console
    console.log('Data:', data);

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            // La requête a réussi, manipulez la réponse ici
            console.log(xhr.responseText);
        } else {
            // La requête a échoué
            console.error('Erreur AJAX. Statut:', xhr.status, 'Message:', xhr.statusText);
        }
    };

    // Envoyer la requête avec les données
    xhr.send(data);
}


