<?php

require_once("genericView.php");

class ViewHome extends GenericView
{

    //Constructeur
    public function __construct()
    {
        parent::__construct();

    }

    
    public static function searchBar(){
        ?>
        <h18>Recherche d'auteur</h18>
        <form id="searchForm">
            <label for="authorName">Nom de l'auteur (partiel ou complet) : </label>
            <input type="text" id="authorName" name="authorName">
        </form>
        <?php

    }

    public static function displayResultsAuthor($results)
    {
        ?>
        <div id="searchResults">
            <?php if (empty($results)) : ?>
                Aucun résultat trouvé.
            <?php else : ?>
                <?php foreach ($results as $result) : ?>
                    <div class="author-info">
                        <h2><?php echo htmlspecialchars($result['name']); ?></h2>
                        <p>Nombre de publications: <?php echo $result['nbpublications'] ?? 'N/A'; ?></p>
                        <p>Affiliation: <?php echo $result['affiliation'] ?? 'N/A'; ?></p>
                        <p id="publicationname" style="display:none;">Publications: <?php echo $result['publicationname'] ?? 'N/A'; ?></p>
                        <button onclick="togglePublications(this)">Afficher les publications</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <script>
            function togglePublications(button) {
                var publicationname = button.parentNode.querySelector('#publicationname');
                if (publicationname.style.display === "none") {
                    publicationname.style.display = "block";
                    button.textContent = "Masquer les publications";
                } else {
                    publicationname.style.display = "none";
                    button.textContent = "Afficher les publications";
                }
            }
        </script>
        <?php
    }

    public static function displayResultsAffiliation($results)
    {
        if (empty($results)) {
            echo "Aucun résultat trouvé.";
        } else {
            foreach ($results as $result) {
                echo '<div class="author-info">';
                echo '<h2>' . htmlspecialchars($result['name']) . '</h2>';
                echo '<p>Nombre de publications: ' . ($result['nbpublications'] ?? 'N/A') . '</p>';
                echo '<p>Affiliation: ' . ($result['affiliation'] ?? 'N/A') . '</p>';
                echo '<p id="publicationname" style="display:none;">Publications: ' . ($result['publicationname'] ?? 'N/A') . '</p>';
                echo '<button onclick="togglePublications(this)">Afficher les publications</button>';
                echo '</div>';
            }
        }
    }

    public function displayResultsTfIdf(){

    }

    public function showDefaultMessage() {

        echo '
        Sélectionnez la recherche que vous voulez faire
        ';


    }

}
?>