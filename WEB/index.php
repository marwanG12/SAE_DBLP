<?php

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DBLP</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
    <script src="JS/script.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-GLhlTQ8i1z9w8I+ndXqD6U6P/R1nhoIR/1P+oihRZ8Yq0s1o5Uj2Q+1paf/vSlB+" crossorigin="anonymous">
</head>

    <header>
    <h1>DBLP</h1>
    </header>

    <body>
        
        <!-- <div id="connectionStatus">
        <button class="btn-connect" type="button" onclick="testDatabaseConnection()">Tester la Connexion</button>
        </div> 
        <br> -->

        

        <div class="searching>
        <form id="searchForm">

            <div class="search-box">
                <button class="btn-search">
                    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512" style="fill: white !important;">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                    </svg>
                </button>

                <input type="text" class="input-search" placeholder="Type to Search...">
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




        <div id="searchResults">
            <!-- Les résultats de la recherche seront affichés ici -->
        </div>

        
    </body>
</html>