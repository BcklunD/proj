<?php

function navbar() {
    $page = getParam('page');
    ?>
    <navbar id='navbar'>
        <div class="navbar-header">
            <a href="index.php">
                <h2>Proj</h2>
            </a>
        </div>
        <a class='navlink' href="index.php">Funktioner</a>
        <a class='navlink' href="index.php">Pris</a>
        <div class="nav-menu">
            <a href="javascript:toggleMenu()">
                <div class="nav-burger">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>
            <div id="menu-box">
                <ul id="menu-list">
                    <li class="<?= str_contains($page, "tak") ? "active" : "" ?>"><a href="index.php?page=pages/tak.php">Tak</a></li>
                    <li class="<?= str_contains($page, "golv") ? "active" : "" ?>"><a href="index.php?page=pages/golv.php">Golv</a></li>
                    <li class="<?= str_contains($page, "vaggar") ? "active" : "" ?>"><a href="index.php?page=pages/vaggar.php">Väggar</a></li>
                </ul>
            </div>
        </div>
    </navbar>
    <?php
}