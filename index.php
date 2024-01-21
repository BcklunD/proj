<?php
require_once("include/layout.inc.php");
require_once("include/standard.inc.php");
require_once("include/widgets.inc.php");
?>

<!DOCTYPE HTML>
<html xmlns:og="http://ogp.me/ns#">
    <head>
        <title>Proj | Projektering</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href='index.css' rel='stylesheet'>
        <style>@import url('https://fonts.googleapis.com/css2?family=Exo&display=swap');</style>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript" src="script/common.js"></script>
        <link rel="icon" type="image/x-icon" href="  favicon.png">
    </head>
    <body>
        <?php
            menu();
            navbar();
            $page = getParam("page");
            echo "<main id='content'>";
                if (notEmpty($page))
                    include($page);
                else {
                    echo "
                        <div id='home'>
                            <h1>Välkommen till Proj</h1>
                            <p class='subtitle'>Din personliga projekterings-hjälp</p>
                        </div>";
                }
            echo "</main>";
        ?>
    </body>
</html>