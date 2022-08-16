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
    <link type="text/css" rel="stylesheet" href="css/detalhes.css">
</head>
<body>
<header>
    <a href="menu.php">Inventário</a>
    <div class="vl"></div>
    <a href="concluidas.php">Material concluído</a>
</header>
    <a href="menu.php" id="voltar"> ↩ </a>
    <p></p>
<?php
//get pq é atraves do url
$cod = $_GET['cod'];
$username = $_SESSION['username'];
date_default_timezone_set("Europe/Lisbon");

$query = "SELECT * FROM btstpeca WHERE codigo='$cod'";
$result = pg_query($connection, $query);

$query3 = "SELECT * FROM btstcomentario WHERE btstpeca_codigo='$cod'";
$result3 = pg_query($connection, $query3);

if(isset($_POST['comentar']) && !empty($_POST['comentario'])) {
    $comentario = $_POST ['comentario'];
    $data = date('Y-m-d');

    $query2 = "INSERT INTO btstcomentario (txtcoment, datahora, btstpeca_codigo, btsttrabalhador_username) VALUES ('$comentario', '$data', '$cod', '$username')";
    $result2 = pg_query($connection, $query2);

    //$aviso = "Comentário adicionado !";
    header("location: menu.php");
}

if(pg_affected_rows($result) > 0) for ($i=0; $i<pg_affected_rows($result); $i++){
    $arrayDetalhe = pg_fetch_array($result);
    $datahoje = date('Y-m-d');
    $date = $arrayDetalhe['prazoentrega'];
    if($datahoje<=$date && $arrayDetalhe['concluida']=="f"){
         $d= "<span class='dataverde' style='font-size: 10pt'>" . $date . "</span>";
    }
    if($datahoje>$date && $arrayDetalhe['concluida']=="f"){
        $d= "<span class='datavermelha' style='font-size: 10pt'>" . $date . "</span>";
    }

    if($arrayDetalhe['concluida']=="t"){
        $d= "<span style='font-size: 10pt; color: black'>" . $date . "</span>";
    }
    ?>
    <span style="display: none"> <?php echo (int)$arrayDetalhe['concluida'] ?> </span>

<div class="esq">
    <p>
    <a href="upload/<?php echo $arrayDetalhe['imagem']?>" target="_blank">
        <img src="upload/<?php echo $arrayDetalhe['imagem']?>" height="120" width="80" alt="img">
    </a>
<br><?php echo "<span>Nº encomenda: </span>"."<b style='font-family: Montserrat semibold, sans-serif'>".$arrayDetalhe['codigo']."</b>";?>
    <br> <?php echo "<span>Nome cliente: </span>"."<b style='font-family: Montserrat semibold, sans-serif'>".$arrayDetalhe['nome']."</b>";?>
    <br> <?php echo "<span>Prazo entrega: </span>"."<b style='font-family: Montserrat semibold, sans-serif'>".$d."</b>";?>
    <br> <?php echo "<span>Quantidade: </span>"."<b style='font-family: Montserrat semibold, sans-serif'>". $arrayDetalhe['quantidade']."</b>";?>
    <br><br><br>
    <?php
    if($arrayDetalhe['concluida']=="t"){
        echo "<span>Estado: </span>"."<b style='font-family: Montserrat semibold, sans-serif'>". "Concluída" ."</b>";
        echo "<br> <span>Concluída por: </span>"."<b style='font-family: Montserrat semibold, sans-serif'>". $arrayDetalhe['btsttrabalhador_username1']."</b>";
    }
    else{
        echo "<span>Estado: </span>"."<b style='font-family: Montserrat semibold, sans-serif'>". "Não concluída" ."</b>";
    }
    ?>
</p>
</div>
<?php }

if(pg_affected_rows($result3) > 0){
    for ($i=0; $i<pg_affected_rows($result3); $i++){
        $arrayComent = pg_fetch_array($result3);
        $date = date("d/m", strtotime($arrayComent['datahora']));
        ?>
<div class="dir">
    <p>
        <?php echo $arrayComent['txtcoment']; ?>
        <br>
        <span>
            <?php echo $date.", ".$arrayComent['btsttrabalhador_username'];?>
            <a id="remover" href="remover.php?acao=del&cod=<?php echo $arrayComent['codigo']?>">Remover</a>
        </span>
    </p>
    <?php }} ?>
    <form action="menu.php" method="POST">
        <label>
            <textarea id="comentario" name="comentario" rows="4" cols="50" placeholder="Comenta aqui"></textarea>
        </label>
        <br>
        <label>
            <input id="comentar" type="submit" name="comentar" value="Comentar">
        </label>
    </form>
</div>
</body>
</html>
