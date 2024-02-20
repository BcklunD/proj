<?php
require_once("include/database.inc.php");
require_once("include/layout.inc.php");
require_once("include/standard.inc.php");
require_once("include/widgets.inc.php");

define("PREFIX", "");
connect();
?>

<!DOCTYPE HTML>
<html xmlns:og="http://ogp.me/ns#">
    <head>
        <title>Proj | Projektering</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href='css/index.css?v=1' rel='stylesheet'>
        <style>@import url('https://fonts.googleapis.com/css2?family=Exo&display=swap');</style>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://kit.fontawesome.com/7666aab81e.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="script/common.js?v=1"></script>
        <link rel="icon" type="image/x-icon" href="favicon.png">
    </head>
    <body>
        <?php
            navbar();
            $page = getParam("page");
            echo "<main id='content'>";
                if (notEmpty($page))
                    include($page);
                else {
                    echo "
                        <div id='home'>
                            <h1 id='title'></h1>
                            <p class='subtitle'>Effektivisera din projektering och materialvalsprocess med vårt intuitiva verktyg</p>
                        </div>";
                }
            echo "</main>";
        ?>
    </body>
</html>
<script>
$(document).ready(() => {
    const text = "Projektering på ett enkelt sätt";
    for(let i = 0; i < text.length; i++) {
        setTimeout(() => {
            $("#title").text(text.substring(0, i + 1));
        }, 100 * i + Math.floor(Math.random() * 50));
    }
});
</script>