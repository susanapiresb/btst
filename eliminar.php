<?php
session_start();
include('verifica.php');
$str = "dbname=ddvv0meaeaknjq user=ziytsfqigzzvhy password=6c16f29cf98ff490a5b01096fd076a289e318d743a759ecbd41e5db3fd0faa82 host=ec2-54-194-211-183.eu-west-1.compute.amazonaws.com port=5432";
$connection = pg_connect($str);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BTST</title>
    <link type="text/css" rel="stylesheet" href="css/header.css">
    <link type="text/css" rel="stylesheet" href="css/eliminar.css">
</head>
<body>

<header>
    <a href="menu.php">Inventário</a>
    <div class="vl"></div>
    <a href="concluidas.php">Material concluído</a>
</header>

<?php
$cod = $_GET['cod'];

if(isset($_POST['sim'])){
    $query3 = "DELETE FROM btstcomentario WHERE btstpeca_codigo='$cod'";
    $result3 = pg_query($connection, $query3);
    $query2 = "DELETE FROM btstpeca WHERE codigo='$cod'";
    $result2 = pg_query($connection, $query2);

    $erro = "ENCOMENDA Nº".$cod." ELIMINADA COM SUCESSO !";
}
if(isset($_POST['nao'])){
    header("location: menu.php");
}

$query = "SELECT * FROM btstpeca WHERE codigo='$cod'";
$result = pg_query($connection, $query);
for ($i=0; $i<pg_affected_rows($result); $i++){
    $array = pg_fetch_array($result);
    $cod = $array['codigo'];
?>

<h2>Desejas mesmo eliminar a encomenda nº <?php echo $array['codigo']; ?> ? </h2>
<form action="eliminar.php?cod=<?php echo $cod ?>" method="post">
    <input type="submit" name="sim" value="SIM">
    <input type="submit" name="nao" value="NÃO">
</form>
<?php } ?>

<?php if(isset($erro)){?>
        <div class="eliminada">
    <p id="erro"><?php echo $erro;?></p>
    <p id="voltar"><a href="menu.php">Voltar</a></p>
        </div>
<?php }?>

</body>
</html>
