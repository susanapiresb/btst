<?php
session_start();
include('verifica.php');
$str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
$connection = pg_connect($str);

$username = $_SESSION['username'];

if (isset($_POST['adicionar'])) {
    $codigo = $_POST ['codigo'];
    $nome = $_POST ['nome'];
    $quantidade = $_POST['quantidade'];
    $prazoentrega = $_POST['prazoentrega'];

    /*if(!empty($_FILES['arquivo']['name'])){

        $extensao = strtolower(substr($_FILES['arquivo']['name'], -4));
        $novo_nome = md5(time(). $extensao);
        $diretorio = "upload/";

        move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);
    /*}
    else{
        $novo_nome ="../upload/imagem.png";
    }*/

    $target_dir = "../BTST/upload/";
    $target_file = $target_dir . basename($_FILES["arquivo"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $image=basename( $_FILES["arquivo"]["name"]);
    move_uploaded_file($_FILES["arquivo"]["tmp_name"], $target_dir.$_FILES['arquivo']['name']);

    //para verificar que nao há outro filme igual
    $query = "SELECT codigo FROM btstpeca WHERE codigo = '$codigo'";
    $resultado = pg_query($connection, $query);


    if (pg_affected_rows($resultado) != 0){    //se essa peça ja foi adicionada
        $erro = "Peça já adicionada";
        //header('location: index.php');
    }
    else {   //se essa peça ainda não foi adicionada, coloca-a na base de dados
        $query1 = "INSERT INTO btstpeca (codigo, nome, imagem, concluida, btsttrabalhador_username, quantidade, prazoentrega) VALUES ('$codigo','$nome','$image',false, '$username', '$quantidade', '$prazoentrega')";
        $resultado1 = pg_query($connection, $query1);
        $erro = "Peça adicionada com sucesso!";
        //header('location: menuantigo.php');
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