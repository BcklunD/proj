<?php

function combobox($name, $values, $selectedValue, $label = "", $onChange = "") {
    if (notEmpty($label))
        echo "<label class='form-label' for='$name'>$label</label>";
    echo "<select class='form-input' id='$name' name='$name' onChange='$onChange'>";
    foreach($values as $value) {
        $selected = $value[0] == $selectedValue ? "selected" : "";
        echo "<option value='$value[0]' $selected>$value[1]</option>";
    }
    echo "</select>";
}

function textbox($name, $value, $label = "", $onChange = "") {
    if (notEmpty($label))
        echo "<label class='form-label' for='$name'>$label</label>";
    echo "<input type='text' class='form-input' id='$name' name='$name' onInput='$onChange' value='$value' />";
}

function checkbox($name, $value, $label = "", $onChange = "") {
    if (notEmpty($label))
        echo "<label class='checkbox-label' for='$name'>$label</label>";
    $checked = $value == 1 ? "checked" : "";
    echo "<input type='checkbox' class='checkbox' id='$name' name='$name' onChange='$onChange' $checked />";
}

function rangeInput($name, $value, $min, $max, $label = "", $onChange = "") {
    if (notEmpty($label))
        echo "<label class='form-label' for='$name'>$label</label>";
    $onChange = isEmpty($onChange) ? "" : $onChange;
    echo "
    <div class='form-input range-input'>
        <input type='range' id='$name' name='$name' value=$value min=$min max=$max onChange='$onChange' />
        <span id=value_$name>$value</span>
    </div>";
    echo "
    <script>
    $('#$name').on('input', () => {
        $('#value_$name').text($('#$name').val());
    });
    </script>";
}

function buildItemModal($type) {
    echo "
    <dialog id='item-modal'>
        <button id='modal-close' onClick='toggleItemModal()'><i class='fa-solid fa-xmark'></i></button>
        <h1>Header</h1>
        <p>text</p>
    </dialog>";
}