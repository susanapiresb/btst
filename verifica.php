<?php
$str = "dbname=ddvv0meaeaknjq user=ziytsfqigzzvhy password=6c16f29cf98ff490a5b01096fd076a289e318d743a759ecbd41e5db3fd0faa82 host=ec2-54-194-211-183.eu-west-1.compute.amazonaws.com port=5432";
$connection = pg_connect($str);

$username = $_SESSION['username'];
$trabalhador = "SELECT username FROM btsttrabalhador WHERE username='$username'";
$result = pg_query($connection, $trabalhador);

if (pg_affected_rows($result) == 0) {
    session_destroy();
    header('location: index.php');
}
