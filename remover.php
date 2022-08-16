<?php
$str = "dbname=ddvv0meaeaknjq user=ziytsfqigzzvhy password=6c16f29cf98ff490a5b01096fd076a289e318d743a759ecbd41e5db3fd0faa82 host=ec2-54-194-211-183.eu-west-1.compute.amazonaws.com port=5432";
$connection = pg_connect($str);

session_start();

$cod = $_GET['cod'];

if ($_GET['acao'] == 'del'){
    $sqleliminar = "DELETE FROM btstcomentario where codigo='$cod'";
    $apagar = pg_query($connection, $sqleliminar);

    header("location: menu.php");
}
