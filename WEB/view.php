<!DOCTYPE html>
<html>

<head>
    <title>Afficher des cercles à partir de données de nœuds</title>
</head>

<body>
    <script src="./resources/js/jquery.min.js"></script>
    <script src="./resources/js/drawing.js" type="module"></script>
    <script src="./resources/js/info.js" type="module"></script>
    <svg id="render" xmlns="http://www.w3.org/2000/svg" id="monSVG"></svg>

    <div class="inline">
        <input type="text" id="search" placeholder="Search for an author">
        <div>
            <label for="weight">With weight</label>
            <input type="checkbox" id="weight" name="weight" value=0>
        </div>
        
    </div>
    

    <style>

        .inline {
            display: inline-block;
            position:absolute;
            top: 10px;
            left: 10px;
        }

        .d-flex {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        #render {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            user-select: none;
        }

        #primaryInfo {
            background-color: rgba(0, 0, 0, 0.1);
        }

        #publications {
            background-color: rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }
    </style>
</body>

</html>