<?php
session_start();
include('verifica.php');
$str = "dbname=postgres user=postgres password='postgres' host=localhost port=5432";
$connection = pg_connect($str);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BTST</title>
    <link type="text/css" rel="stylesheet" href="../BTST/css/header.css">
    <link type="text/css" rel="stylesheet" href="../BTST/css/editar.css">
</head>
<body>

<header>
    <a href="menu.php">Inventário</a>
    <div class="vl"></div>
    <a href="concluidas.php">Material concluído</a>
</header>
    <a href="menu.php" id="voltar"> ↩ </a>

<?php
$cod = $_GET['cod'];
$username = $_SESSION['username'];

if(isset($_POST['editar'])){
    $codigo = $_POST ['codigo'];
    $nome = $_POST ['nome'];
    $quantidade = $_POST['quantidade'];
    $prazoentrega = $_POST['prazoentrega'];

    $query = "SELECT * FROM btstpeca WHERE codigo='$cod'";
    $result = pg_query($connection, $query);
    for ($i=0; $i<pg_affected_rows($result); $i++){
        $array = pg_fetch_array($result);

    if(!empty('nome')){
        $query2 = "UPDATE btstpeca SET nome='$nome', quantidade='$array[quantidade]', prazoentrega='$array[prazoentrega]' WHERE codigo='$cod'";
        $result2 = pg_query($connection, $query2);
    }
    if(!empty('quantidade')){
        $query3 = "UPDATE btstpeca SET quantidade='$quantidade', nome='$array[nome]', prazoentrega='$array[prazoentrega]' WHERE codigo='$cod'";
        $result3 = pg_query($connection, $query3);
    }
    if(!empty('prazo')){
        $query4 = "UPDATE btstpeca SET prazoentrega='$prazoentrega', nome='$array[nome]', quantidade='$array[quantidade]' WHERE codigo='$cod'";
        $result4 = pg_query($connection, $query4);
    }
    }
}
?>

<p class="aviso">⚠ EDITAR APENAS UMA CARATERÍSTICA DE CADA VEZ ⚠</p>
<?php

$query5 = "SELECT * FROM btstpeca WHERE codigo='$cod'";
$result5 = pg_query($connection, $query5);

if(pg_affected_rows($result5)>0){
    for ($i=0; $i<pg_affected_rows($result5); $i++){
        $array = pg_fetch_array($result5);
        $cod = $array['codigo'];    //codigo filme tabela
        $date = date("d/m", strtotime($array['prazoentrega']));
        ?>
        <div class="material">
            <br> <img src="../BTST/upload/<?php echo $array['imagem']?>" height="120" width="80" alt="img">
            <br> <?php echo "<span>Nº encomenda: </span>"."<b>".$array['codigo']."</b>"; ?>
            <br> <?php echo "<span>Nome cliente: </span>"."<b>".$array['nome']."</b>"; ?>
            <br> <?php echo "<span>Quantidade: </span>"."<b>".$array['quantidade']."</b>"; ?>
            <br> <?php echo "<span>Prazo entrega: </span>"."<b>".$date."</b>"; ?>
        </div>

<div class="conteudo">
    <form action="editar.php?cod=<?php echo $cod ?>" method="POST">
        <p>
            <label>
                Nº encomenda
                <input id="nencomenda" type="text" name="codigo" placeholder="<?php echo $array['codigo'];?>">
            </label>
        </p>

        <p>
            <label>
                Nome cliente
                <input id="nomecliente" type="text" name="nome" placeholder="<?php echo $array['nome']; ?>">
            </label>
        </p>

        <p>
            <label>
                Quantidade
                <input type="number" name="quantidade" placeholder="<?php echo $array['quantidade']; ?>" min="0">
            </label>
        </p>

        <p>
            <label>
                Prazo entrega
                <input type="date" name="prazoentrega" placeholder="<?php echo $array['prazoentrega']; ?>">
            </label>
        </p>

        <label>
            <input id="editar" type="submit" name="editar" value="Editar">
        </label>
    </form>
    <?php }} ?>
</div>

</body>
</html>
