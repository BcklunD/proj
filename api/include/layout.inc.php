<?php

function navbar() {
    ?>
    <navbar id='navbar'>
        <div class="navbar-header">
            <a href="index.php">
                <h2>Proj</h2>
            </a>
        </div>
        <div class="nav-menu">
            <a href="javascript:toggleMenu()">
                <div class="nav-burger">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>
        </div>
    </navbar>
    <?php
}

function menu() {
    $page = getParam('page');
    ?>
    <aside id='main-menu'>
        <div id="menu-top"></div>
        <div id="menu-inner">
            <ul id="menu-list">
                <li class="<?= str_contains($page, "tak") ? "active" : "" ?>"><a href="index.php?page=pages/tak.php">Tak</a></li>
                <li class="<?= str_contains($page, "golv") ? "active" : "" ?>"><a href="index.php?page=pages/golv.php">Golv</a></li>
                <li class="<?= str_contains($page, "vaggar") ? "active" : "" ?>"><a href="index.php?page=pages/vaggar.php">Väggar</a></li>
            </ul>
        </div>
    </aside>
    <?php
}