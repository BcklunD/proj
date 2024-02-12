<?php
require_once __DIR__.'/../include/vaggar.inc.php';

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
        <p class='filter-header'>Väggar</p>
        <div id='filters'>";
        textbox("namn", "", "Namn", "sortVaggar()");
        combobox("tillverkare", $tillverkare, null, "Tillverkare", "sortVaggar()");
        rangeInput("ljudkrav", $minLjud, $minLjud, $maxLjud, "Ljudkrav (dB)", "sortVaggar()");
    echo "</div></aside>";
    echo "<div id='item-container'>";
    foreach($vaggar as $vagg)
        vaggCard($vagg);
    echo "</div>
</div>";

buildItemModal('vagg');

function vaggCard($vagg) {
    if (strstr($vagg->ljudkrav, "-"))
        $vagg->ljudkrav = explode("-", $vagg->ljudkrav)[1];
    echo "<a href='javascript:void(0)' onclick='toggleItemModal(this)' class='item-card' data-namn='$vagg->namn' data-tillverkare='$vagg->tillverkare' data-ljudkrav='$vagg->ljudkrav'>
            <p class='card-header'>$vagg->tillverkare</p>
            <p>$vagg->namn</p>
            <p>Ljudkrav: $vagg->ljudkrav db</p>
    </a>";
}

?>
<script>
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