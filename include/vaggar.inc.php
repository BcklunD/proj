<?php

function buildVaggarSQL() {
    require_once '../vendor/autoload.php';

    $workbook = \PhpOffice\PhpSpreadsheet\IOFactory::load("../excel/väggar.ods");
    $sheet = $workbook->getActiveSheet();
    $row = 4;
    $nextLopnr = findValue("select max(lopnr) from vagg", 0) + 1;
    $inserts = [];
    $emptyRowCount = 0;
    while(true) {
        $littera = trim(utf8_dec($sheet->getCellByColumnAndRow(3, $row)->getValue()));
        if (isEmpty($littera)) {
            if ($emptyRowCount++ >= 3)
                break;
            $row++;
            continue;
        }
        $emptyRowCount = 0;

        $tillverkare = trim(utf8_dec($sheet->getCellByColumnAndRow(5, $row)->getValue()));
        $ljudkrav = utf8_dec($sheet->getCellByColumnAndRow(6, $row)->getValue());
        $lopnr = $nextLopnr++;
        $inserts[] = "($lopnr, '$littera', '$tillverkare', '$ljudkrav')";

        
        // echo '$vagg = new stdClass();<br/>
        // $vagg->namn = "'.$littera. '";<br/>
        // $vagg->tillverkare = "'. trim(utf8_decode($sheet->getCellByColumnAndRow(5, $row)->getValue())) .'";<br/>
        // $vagg->ljudkrav = "'. utf8_decode($sheet->getCellByColumnAndRow(6, $row)->getValue()) .'";<br/>
        // $vaggar[] = $vagg;<br/>';
        $row++;
    }
    $values = "";
    foreach($inserts as $insert) {
        if ($values != "")
            $values .= ",<br/>";
        $values .= "$insert";
    }
    if (notEmpty($values))
        echo "INSERT INTO vagg (lopnr, littera, tillverkare, ljudkrav) VALUES <br/>$values;";
}