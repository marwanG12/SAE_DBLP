<?php

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DBLP</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
    <script src="JS/script.js"></script>
</head>
<body>
    
    <h1>DBLP</h1>
    

    <div id="connectionStatus">
    <button type="button" onclick="testDatabaseConnection()">Tester la Connexion</button>
    </div> <!-- C'est ici que l'élément avec l'ID "connectionStatus" est créé -->
    <br>

    

    <form id="searchForm">
        <label for="authorName">Rechercher : </label>
        <input type="text" class="input" name="txt" onmouseout="this.value = ''; this.blur();">
        <button type="button" onclick="searchAuthors()">Rechercher par Auteur</button>
        <button type="button" onclick="searchByAffiliation()">Rechercher par Affiliation</button>
        <button type="button" onclick="searchByTextQuery()">Rechercher par Requête Textuelle</button>
    </form>
    <i class="fas fa-search"></i>

    


    <div id="searchResults">
        <!-- Les résultats de la recherche seront affichés ici -->
    </div>

    
</body>
</html>