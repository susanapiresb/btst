<?php
session_start();
include('verifica.php');
$str = "dbname=ddvv0meaeaknjq user=ziytsfqigzzvhy password=6c16f29cf98ff490a5b01096fd076a289e318d743a759ecbd41e5db3fd0faa82 host=ec2-54-194-211-183.eu-west-1.compute.amazonaws.com port=5432";
$connection = pg_connect($str);

$username = $_SESSION['username'];

if (isset($_POST['adicionar'])) {
    $codigo = $_POST ['codigo'];
    $nome = $_POST ['nome'];
    $quantidade = $_POST['quantidade'];
    $prazoentrega = $_POST['prazoentrega'];

    $target_dir = "upload/";
    $target_file = $target_dir . basename($_FILES["arquivo"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $image=basename( $_FILES["arquivo"]["name"]);
    move_uploaded_file($_FILES["arquivo"]["tmp_name"], $target_dir.$_FILES['arquivo']['name']);

    //para verificar que nao há outro filme igual
    $query = "SELECT codigo FROM ziytsfqigzzvhy@heroku.btstpeca WHERE codigo = '$codigo'";
    $resultado = pg_query($connection, $query);


    if (pg_affected_rows($resultado) != 0){    //se essa peça ja foi adicionada
        $erro = "Peça já adicionada";
        //header('location: index.php');
    }
    else {   //se essa peça ainda não foi adicionada, coloca-a na base de dados
        $query1 = "INSERT INTO ziytsfqigzzvhy@heroku.btstpeca (codigo, nome, imagem, concluida, btsttrabalhador_username, quantidade, prazoentrega) VALUES ('$codigo','$nome','$image',false, '$username', '$quantidade', '$prazoentrega')";
        $resultado1 = pg_query($connection, $query1);
        $erro = "Peça adicionada com sucesso!";
        //header('location: menu.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BTST</title>
    <link type="text/css" rel="stylesheet" href="css/header.css">
    <link type="text/css" rel="stylesheet" href="css/adicionar.css">
</head>
<body>

<header>
    <a href="menu.php">Inventário</a>
    <div class="vl"></div>
    <a href="concluidas.php">Material concluído</a>
</header>
<a href="menu.php" id="voltar"> ↩ </a>

<div class="conteudo">
    <form action="adicionar.php" method="POST" enctype="multipart/form-data">
        <?php if(isset($erro)){?>
            <p id="erro"><?php echo $erro;?></p>
        <?php }?>
        <p>
            <label>
                Nº encomenda
                <input type="text" name="codigo" placeholder="PO123">
            </label>
        </p>

        <p>
            <label>
                Nome cliente
                <input type="text" name="nome" placeholder="BTST">
            </label>
        </p>

        <p>
            <label>
                Quantidade
                <input id="quantidade" type="number" name="quantidade" placeholder="1" min="1">
            </label>
        </p>

        <p>
            <label>
                Prazo entrega
                <input id="prazo" type="date" name="prazoentrega">
            </label>
        </p>

        <p>
            <label>
                Imagem
                <input id="imagem" type="file" name="arquivo">
            </label>
        </p>

        <br>
        <label>
            <input id="adicionar" type="submit" name="adicionar" value="Adicionar">
        </label>

</div>
</body>
</html>
