<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "sepms"
);

if (!$conn) {
    die("Database connection failed.");
}

?>