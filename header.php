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

        if (isset($_SESSION["name"]) && $_SESSION["name"] != "") {

            echo htmlspecialchars($_SESSION["name"]);

        } else {

            echo "User";

        }

        ?>

    </div>


</header>