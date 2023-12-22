<?php

session_start();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>DBLP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="JS/script.js"></script>
    <link rel="stylesheet" href="./resources/css/style.css"/>
    <title>DBLP</title>
    <link rel="icon" href="Assets/Images/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script type="text/javascript" src="./resources/js/jquery.min.js"></script>
</head>

<header>
    <h1>DBLP</h1>
</header>

<body>

    <a id="graphViz" href="./view.php">Graph Vizualisation<a>

    <div class="searching">
        <form id="searchForm">

            <div class="search-box">
                <button type="button" class="btn-search" onclick="performSearch()" title="Rechercher">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                <input type="text" class="input-search" placeholder="Type to Search..." id="authorName" name="authorName">
            </div>

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

</body>

</html>