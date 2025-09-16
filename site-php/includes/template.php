<!doctype html>
<html lang="fr">

<head>
    <title>Liste des véhicules</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" href="img/favicon.ico">
    <!--<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="css/site.css" />

    <?php
    if (isset($region_links)) {
        echo $region_links;
    }
    ?>
</head>

<body>
    <header>
	    SITE WEB EN PHP
    </header>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <main>
                    <?= $region_content; ?>
                </main>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <footer>
                    &copy; Steeve Godmaire 2021
                </footer>
            </div>
        </div>
    </div>

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <!--<script type="text/javascript" src="js/site.js"></script>-->

    <?php
    if (isset($region_scripts)) {
        echo $region_scripts;
    }
    ?>
</body>

</html>