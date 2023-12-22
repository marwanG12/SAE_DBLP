<!DOCTYPE html>
<html>
  <head>
    <title>Afficher des cercles à partir de données de nœuds</title>
  </head>
  <body>
    <script src="./jquery.min.js"></script>
    <script src="./js/drawing.js" type="module"></script>
    <script src="./js/info.js" type="module"></script>
    <svg id="render" xmlns="http://www.w3.org/2000/svg" id="monSVG"></svg>

    <input type="text" id="search" placeholder="Search for an author" onkeyup="searchAuthor(this.value)">
    <div id="infoBox">
      <div id="primaryInfo">
        <p id="authorName">Name</p>
        <p id="authorPublications">Nb</p>
      </div>
      <div id="publications">
      </div>
    </div>

    <style>
      #render {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        user-select: none;
      }

      #infoBox {
        position: absolute;
        top: 10px;
        right: 60px;
        width: 250px;
        height: 60%;
        padding: 10px;
        overflow: auto;
        display:flex;
        flex-direction: column;
      }

      #primaryInfo {
        background-color:rgba(0,0,0,0.1);
      }

      #publications {
        background-color:rgba(0,0,0,0.1);
        margin-top:10px;
      }

      #search {
        position:absolute;
        top:10px;
        left:10px;
      }

    </style>
  </body>
</html>

