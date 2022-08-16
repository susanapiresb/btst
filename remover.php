<?php
$str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
$connection = pg_connect($str);

session_start();

$cod = $_GET['cod'];

if ($_GET['acao'] == 'del'){
    $sqleliminar = "DELETE FROM btstcomentario where codigo='$cod'";
    $apagar = pg_query($connection, $sqleliminar);

    header("location: menu.php");
}
