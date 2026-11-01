<?php

if (isset($_GET["input_username"]) && isset($_GET["input_password"]) && isset($_GET["input_name"]) && isset($_GET["input_email"])) {
    echo $_GET["input_username"];
    echo "<br>";
    echo $_GET["input_password"];
    echo "<br>";
    echo $_GET["input_name"];
    echo "<br>";
    echo $_GET["input_email"];
    echo "<br>";
}