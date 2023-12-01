<?php

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DBLP</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    
    <h1>DBLP</h1>
    <button type="button" onclick="testDatabaseConnection()">Tester la connexion</button>
    <div id="connectionStatus"></div> <!-- C'est ici que l'élément avec l'ID "connectionStatus" est créé -->
    <br>

    
    <h18>Recherche d'auteur</h18>
    <form id="searchForm">
        <label for="authorName">Nom de l'auteur (partiel ou complet) : </label>
        <input type="text" id="authorName" name="authorName">
        <button type="button" onclick="searchAuthors()">Rechercher par Auteur</button>
        <button type="button" onclick="searchByAffiliation()">Rechercher par Affiliation</button>
        <button type="button" onclick="searchByTextQuery()">Rechercher par Requête Textuelle</button>
    </form>

    


    <div id="searchResults">
        <!-- Les résultats de la recherche seront affichés ici -->
    </div>

    <script>

        

            function testDatabaseConnection() {
                fetch("http://localhost/SAE_DBLP/WEB/PHP/connexion.php") // Appel du fichier PHP pour le test de connexion
                    .then(response => {
                        if (response.ok) {
                            return response.text(); // Renvoie le texte du résultat
                        } else {
                            throw new Error("La requête a échoué.");
                        }
                    })
                    .then(data => {
                        document.getElementById("connectionStatus").textContent = data;
                    })
                    .catch(error => {
                        console.error("Erreur de test de connexion : " + error);
                    });
                }



                    
                function searchAuthors() {
                    const authorName = document.getElementById("authorName").value;
                    if (authorName.trim() === "") {
                        alert("Veuillez entrer un nom pour effectuer la recherche.");
                        return; // Arrêtez la recherche si la requête est vide
                    }
                    fetch("http://localhost/SAE_DBLP/WEB/PHP/auteur.php?authorName=" + authorName)
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
                    fetch("http://localhost/SAE_DBLP/WEB/PHP/affiliation.php?authorName=" + affiliationName)
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

                function searchByTextQuery() {
                    const query = document.getElementById("authorName").value;
                    console.log("Requête en cours : " + query); // Afficher la requête dans la console
                    if (query.trim() === "") {
                        alert("Veuillez entrer une requête pour effectuer la recherche.");
                        return;
                    }

                    fetch("http://localhost/SAE_DBLP/WEB/PHP/tfidf.php?query=" + query)
                        .then(response => response.text()) // Utilisez response.text() pour obtenir le contenu textuel
                        .then(data => {
                            const searchResultsDiv = document.getElementById("searchResults");
                            searchResultsDiv.innerHTML = data; // Ajoutez le contenu renvoyé par le script PHP directement à la div des résultats
                        })
                        .catch(error => {
                            console.error("Erreur de recherche textuelle : " + error);
                        });
                }



    </script>
</body>
</html>