<?php
require_once "include/vaggar.inc.php";

$tillverkare = array();
$tillverkare[] = array(null, "");
$minLjud = 999;
$maxLjud = 0;

foreach($vaggar as $vagg) {
    if (!array_key_exists($vagg->tillverkare, $tillverkare))
        $tillverkare[$vagg->tillverkare] = array($vagg->tillverkare, $vagg->tillverkare);
    if (strstr($vagg->ljudkrav, "-"))
        list($lower, $higher) = explode("-", $vagg->ljudkrav);
    else
        $lower = $higher = $vagg->ljudkrav;
    if ($lower < $minLjud)
        $minLjud = $lower;
    if (notEmpty($higher) && $higher > $maxLjud)
        $maxLjud = $higher;
}

echo "<div id='filter-wrapper'>";
    echo "<aside id='filter-container'>
        <p>Filter</p>
        <div id='filters'>";
        textbox("namn", "", "Namn", "sortVaggar()");
        combobox("tillverkare", $tillverkare, null, "Tillverkare", "sortVaggar()");
        rangeInput("ljudkrav", $minLjud, $minLjud, $maxLjud, "Ljudkrav (dB)", "sortVaggar()");
    echo "</div></aside>";
    echo "<div id='items'>
        <h1>Väggar</h1>
        <div id='item-container'>";
        foreach($vaggar as $vagg)
            vaggCard($vagg);
        echo "</div>
    </div>
</div>";

function vaggCard($vagg) {
    if (strstr($vagg->ljudkrav, "-"))
        $vagg->ljudkrav = explode("-", $vagg->ljudkrav)[1];
    echo "<div class='item-card' data-namn='$vagg->namn' data-tillverkare='$vagg->tillverkare' data-ljudkrav='$vagg->ljudkrav'>";
        echo "<p>$vagg->namn</p>";
        echo "<p>$vagg->tillverkare</p>";
        echo "<p>Ljudkrav: $vagg->ljudkrav</p>";
    echo "</div>";
}

?>
<script>
// let sortTimer = null;
function sortVaggar() {
    const namn = $("input[name='namn']").val().toLowerCase();
    const tillverkare = $("select[name='tillverkare']").val();
    const ljudkrav = $("input[name='ljudkrav']").val();
    document.querySelectorAll(".item-card").forEach(element => {
        let hidden = false;
        hidden = hidden || (namn.length > 0 && !element.dataset.namn.toLowerCase().includes(namn));
        hidden = hidden || (tillverkare != "" && element.dataset.tillverkare != tillverkare);
        hidden = hidden || element.dataset.ljudkrav < ljudkrav;
        $(element).toggleClass("hidden-card", hidden);
    });
}
</script>