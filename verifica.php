<?php
$str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
$connection = pg_connect($str);

$username = $_SESSION['username'];
$trabalhador = "SELECT username FROM btsttrabalhador WHERE username='$username'";
$result = pg_query($connection, $trabalhador);

if (pg_affected_rows($result) == 0) {
    session_destroy();
    header('location: index.php');
}
