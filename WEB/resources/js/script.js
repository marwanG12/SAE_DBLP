function performSearch() {
    console.log("on y entre");
    // Récupérer la valeur de la checkbox sélectionnée
    var selectedTool = document.querySelector('input[name="tools"]:checked').value;

    // Appeler la fonction appropriée en fonction de la checkbox sélectionnée
    if (selectedTool === "author") {
        searchAuthors();
    } else if (selectedTool === "affiliation") {
        searchByAffiliation();
    } else if (selectedTool === "keyword") {
        searchByTextQuery();
    }
}

// Ajoutez un gestionnaire d'événements au bouton de recherche
//document.getElementById("searchButton").addEventListener("click", handleSearch);

function searchAuthors() {
    console.log("on entre ds searchAuthors");
    const authorName = document.getElementById("authorName").value;
    if (authorName.trim() === "") {
        alert("Veuillez entrer un nom pour effectuer la recherche.");
        return; // Arrêtez la recherche si la requête est vide
    }
    fetch("auteur.php?authorName=" + authorName)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Appel de la fonction displayResults pour afficher les données
            displayResultsActors(data);
        })
        .catch(error => {
            console.error("Erreur de recherche : " + error);
        });
}



function displayResultsActors(results) {
    const searchResultsDiv = document.getElementById("searchResults");
    searchResultsDiv.innerHTML = "";

    if (results.length === 0) {
        searchResultsDiv.innerHTML = "Aucun résultat trouvé.";
    } else {
        results.forEach(result => {
            const authorInfo = document.createElement("div");
            authorInfo.classList.add("author-info");

            const name = document.createElement("h2");
            name.textContent = result.name;

            const nbpublications = document.createElement("p");
            nbpublications.textContent = `Nombre de publications: ${result.nbpublications || 'N/A'}`;

            const affiliation = document.createElement("p");
            affiliation.textContent = `Affiliation: ${result.affiliation || 'N/A'}`;

            const publicationname = document.createElement("p");
            publicationname.textContent = `Publications: ${result.publicationname || 'N/A'}`;
            publicationname.style.display = "none"; // Cacher par défaut

            // Ajoutez un bouton pour basculer l'affichage des données supplémentaires
            const toggleButton0 = document.createElement("button");
            toggleButton0.textContent = "Afficher les publications";
            toggleButton0.addEventListener("click", () => {
                if (publicationname.style.display === "none") {
                    publicationname.style.display = "block";
                    toggleButton0.textContent = "Masquer les publications";
                } else {
                    publicationname.style.display = "none";
                    toggleButton0.textContent = "Afficher les publications";
                }
            });

            authorInfo.appendChild(toggleButton0);
            authorInfo.appendChild(name);
            authorInfo.appendChild(affiliation);
            authorInfo.appendChild(nbpublications);
            authorInfo.appendChild(publicationname);
            searchResultsDiv.appendChild(authorInfo);
        });
    }
}



function searchByAffiliation() {
    const affiliationName = document.getElementById("authorName").value; // Utilisez un autre élément si nécessaire
    if (affiliationName.trim() === "") {
        alert("Veuillez entrer une affiliation pour effectuer la recherche.");
        return; // Arrêtez la recherche si la requête est vide
    }
    fetch("affiliation.php?authorName=" + affiliationName)
        .then(response => response.json())
        .then(data => {
            // Appel de la fonction displayResults pour afficher les données
            displayResultsAffiliation(data);
        })
        .catch(error => {
            console.error("Erreur de recherche par affiliation : " + error);
        });
}



function displayResultsAffiliation(results) {
    const searchResultsDiv = document.getElementById("searchResults");
    searchResultsDiv.innerHTML = "";

    if (results.length === 0) {
        searchResultsDiv.innerHTML = "Aucun résultat trouvé.";
    } else {
        results.forEach(result => {
            const affiliationInfo = document.createElement("div");
            affiliationInfo.classList.add("affiliation-bloc");

            const name = document.createElement("h2");
            name.textContent = result.name;

            const nbpublications = document.createElement("p");
            nbpublications.textContent = `Nombre de publications: ${result.nbpublications || 'N/A'}`;

            const country = document.createElement("p");
            country.textContent = `Pays: ${result.country || 'N/A'}`;

            const publicationlist = document.createElement("p");
            publicationlist.textContent = `Liste de publications: ${result.publicationlist || 'N/A'}`;
            publicationlist.style.display = "none"; // Cacher par défaut

            // Ajoutez un bouton pour basculer l'affichage des données supplémentaires
            const toggleButton2 = document.createElement("button");
            toggleButton2.textContent = "Afficher les articles publies";
            toggleButton2.addEventListener("click", () => {
                if (publicationlist.style.display === "none") {
                    publicationlist.style.display = "block";
                    toggleButton2.textContent = "Masquer les articles publies";
                } else {
                    publicationlist.style.display = "none";
                    toggleButton2.textContent = "Afficher les articles publies";
                }
            });

            const affiliatedauthors = document.createElement("p");
            affiliatedauthors.textContent = `Auteurs affilies: ${result.affiliatedauthors || 'N/A'}`;
            affiliatedauthors.style.display = "none"; // Cacher par défaut


            // Ajoutez un bouton pour basculer l'affichage des données supplémentaires
            const toggleButton = document.createElement("button");
            toggleButton.textContent = "Afficher les auteurs affilies";
            toggleButton.addEventListener("click", () => {
                if (affiliatedauthors.style.display === "none") {
                    affiliatedauthors.style.display = "block";
                    toggleButton.textContent = "Masquer les auteurs affilies";
                } else {
                    affiliatedauthors.style.display = "none";
                    toggleButton.textContent = "Afficher les auteurs affilies";
                }
            });




            affiliationInfo.appendChild(toggleButton);
            affiliationInfo.appendChild(toggleButton2);

            //affiliationInfo.appendChild(additionalData);

            affiliationInfo.appendChild(name);
            affiliationInfo.appendChild(nbpublications);
            affiliationInfo.appendChild(country);
            affiliationInfo.appendChild(publicationlist);
            affiliationInfo.appendChild(affiliatedauthors);

            searchResultsDiv.appendChild(affiliationInfo);
        });
    }
}

function searchByTextQuery() {
    const query = document.getElementById("authorName").value;
    console.log("Requête en cours : " + query); // Afficher la requête dans la console
    if (query.trim() === "") {
        alert("Veuillez entrer une requête pour effectuer la recherche.");
        return;
    }

    fetch("tfidf.php?query=" + query)
        .then(response => response.text()) // Utilisez response.text() pour obtenir le contenu textuel
        .then(data => {
            const searchResultsDiv = document.getElementById("searchResults");
            searchResultsDiv.innerHTML = data; // Ajoutez le contenu renvoyé par le script PHP directement à la div des résultats
        })
        .catch(error => {
            console.error("Erreur de recherche textuelle : " + error);
        });
}

