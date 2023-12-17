<!doctype html>

<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./Assets/style.css"/>
    <title>App - Ventes</title>
    <link rel="icon" href="Assets/Images/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

    
</head>

<body>

    <div id="gradient-overlay"></div>

    <header>
        <?php
            $controller = new Controller;
            $controller->navigation();
        ?>
    </header>

    <main>
        
        <?php
            global $view;
            echo $view;
        ?>
    </main>

</body>

</html>