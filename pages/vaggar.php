<?php
$namn = getParam("namn");
$tillverkare = getParam("tillverkare");
$ljudkrav = getParam("ljudkrav");

$tillverkares = array();
$tillverkares[] = array(null, "");
$minLjud = 999;
$maxLjud = 0;

$vaggar = rs2objarray(sql("select lopnr, littera as namn, tillverkare, ljudkrav from vagg"));
foreach($vaggar as $vagg) {
    if (!array_key_exists($vagg->tillverkare, $tillverkares))
        $tillverkares[$vagg->tillverkare] = array($vagg->tillverkare, $vagg->tillverkare);
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
        textbox("namn", $namn, "Namn", "filterVaggar()");
        combobox("tillverkare", $tillverkares, $tillverkare, "Tillverkare", "filterVaggar()");
        rangeInput("ljudkrav", $ljudkrav, $minLjud, $maxLjud, "Ljudkrav (dB)", "filterVaggar()");
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
let urlTimer;
function filterVaggar() {
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
    const url = `index.php?page=pages/vaggar.php&namn=${namn}&tillverkare=${tillverkare}&ljudkrav=${ljudkrav}`;
    if (urlTimer != null)
        clearTimeout(urlTimer);
    urlTimer = setTimeout(() => {
        window.history.replaceState({}, "", encodeURI(url));
    }, 1000);
}
filterVaggar();
</script>