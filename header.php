<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="top_header">

    <div class="logo">
        SEPMS
    </div>

    <div class="system_title">
        Student Exchange Program Management System
    </div>

    <div class="user_info">

        <?php

        if (isset($_SESSION["role"])) {

            echo $_SESSION["role"];

        } else {

            echo "User";

        }

        ?>

    </div>

</header>