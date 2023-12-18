<?php

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DBLP</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
    <!-- <script src="JS/script.js"></script> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="JS\script.js"></script>
</head>

    <header>
    <h1>DBLP</h1>
    </header>

    <body>
        
        <!-- <div id="connectionStatus">
        <button class="btn-connect" type="button" onclick="testDatabaseConnection()">Tester la Connexion</button>
        </div> 
        <br> -->

        

        <div class="searching">
        <form id="searchForm">

            <div class="search-box">
                <button type="button" class="btn-search" onclick="performSearch()" title="Rechercher">
                <i class="fa-solid fa-magnifying-glass"></i>

                </button>

                <input type="text" class="input-search" placeholder="Type to Search..." id="authorName" name="authorName">
            </div>


            <!-- <label for="authorName">Rechercher : </label>
            <input type="text" id="authorName" name="authorName">
            <button type="button" onclick="searchAuthors()">Rechercher par Auteur</button>
            <button type="button" onclick="searchByAffiliation()">Rechercher par Affiliation</button>
            <button type="button" onclick="searchByTextQuery()">Rechercher par Requête Textuelle</button> -->
        </form>
        </div>


        <div class="section over-hide z-bigger">
		      <div class="col-12">
						<p class="mb-4">Rechercher par :</p>
					</div>
      
					<div class="col-12 pb-5">
						<input class="checkbox-tools" type="radio" name="tools" id="tool-1" checked value=author>
						<label class="for-checkbox-tools" for="tool-1">
							<i class='uil uil-line-alt'></i>
							Auteur
						</label>
						
                        <input class="checkbox-tools" type="radio" name="tools" id="tool-2" value="affiliation">
						<label class="for-checkbox-tools" for="tool-2">
							<i class='uil uil-vector-square'></i>
							Affiliation
						</label>
                        
                        <input class="checkbox-tools" type="radio" name="tools" id="tool-3" value="keyword">
						<label class="for-checkbox-tools" for="tool-3">
							<i class='uil uil-ruler'></i>
							Mot-clé
						</label>
           </div>
     </div>




        <div class="searchingResult" id="searchResults">
            <!-- Les résultats de la recherche seront affichés ici -->
        </div>

        <!-- <script>
    document.addEventListener('DOMContentLoaded', function() {

            function performSearch() {
                console.log("performSearch called");
                    // Récupérer la valeur de la checkbox sélectionnée
                    var selectedTool = document.querySelector('input[name="tools"]:checked').value;
                
                    // Appeler la fonction appropriée en fonction de la checkbox sélectionnée
                    if (selectedTool === "author") {
                        searchAuthors();
                        console.log("found");
                    } else if (selectedTool === "affiliation") {
                        searchByAffiliation();
                    } else if (selectedTool === "keyword") {
                        searchByTextQuery();
                    }
                }

            function searchAuthors() {
                    const authorName = document.getElementById("authorName").value;
                    if (authorName.trim() === "") {
                        alert("Veuillez entrer un nom pour effectuer la recherche.");
                        return; // Arrêtez la recherche si la requête est vide
                    }
                    fetch("auteur.php?authorName=" + authorName)
                        .then(response => response.json())
                        .then(data => {
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
                            affiliationInfo.classList.add("affiliation-info");

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

            });             -->

        </script>
    </body>
</html>