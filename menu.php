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
    <link rel="icon" type="imagem/png" href="upload/imagem.png" />
    <link type="text/css" rel="stylesheet" href="css/header.css">
    <link type="text/css" rel="stylesheet" href="css/menu.css">
</head>
<body>
<header>
    <a href="menu.php">Inventário</a>
    <!--<div class="vl"></div>-->
    <a href="concluidas.php">Material concluído</a>
</header>

<a href="adicionar.php" id="adicionar">Adicionar novo material</a>

<div class="pesquisarordenar">
<form action="Resultado.php" method="POST">
    <label>
        <input type="text" name="pesquisar" placeholder="Nome cliente">
        <!--<input type="date" name="date" value="<?php //echo(isset($_POST['date'])) ? $_POST['date']:''; ?>" placeholder="Pesquisar por cliente ou prazo">-->
    </label>
    <input type="submit" name="submit" value="Pesquisar" id="pesquisar">
</form>

<form action="menu.php" method="POST">
    <label>
        <select name="ordem">
            <option>Nome cliente/Prazo entrega</option>
            <option value="dasc" class="t">Data (da mais recente para a mais antiga)</option>
            <option value="ddes" class="t">Data (da mais antiga para a mais recente)</option>
            <option value="cdes" class="t">Cliente descendente (Z-A)</option>
            <option value="casc" class="t">Cliente ascendente (A-Z)</option>
        </select>
    </label>
    <input type="submit" name="ordenar" value="Ordenar" id="ordenar">
</form>
</div>

<div class="cabecalho">
    <br>
    <h1>Nº encomenda</h1>
    <h1>Nome cliente</h1>
    <h1>Prazo entrega</h1>
    <p></p><p></p>
</div>

<?php
$campo = "prazoentrega";
$ord = "asc";
if (isset($_POST['ordem'])){
    if ($ordem=$_POST['ordem']) {
        if ($ordem === "ddes") {
            $ord = "desc";
            $ordem='ddes';
        }else if ($ordem==="casc"){
            $campo= "nome";
            $ord= "asc";
            $ordem='dasc';
        } else if ($ordem === "cdes") {
            $campo = "nome";
            $ord = "desc";
            $ordem='ddes';
        }
    }
} ?>

<div class="grelha">
    <?php
    $results_per_page = 3;
    
    $q = "SELECT * from ddvv0meaeaknjq.btstpeca";
    $r = pg_query ($connection, $q);
    echo $r;

    //LIMIT 3 OFFSET 3. RESULTADO: 4 e 5. Aparecem 3 resultados (LIMIT) a partir do 3 (OFFSET) (sem contar com o 3).
    $query = "SELECT * FROM btstpeca WHERE concluida=false ORDER BY $campo $ord";
    $result = pg_query($connection, $query);

    $number_of_results = pg_num_rows($result);

    $number_of_pages = ceil($number_of_results/$results_per_page);

    if(!isset($_GET['page'])){
        $page = 1;
    }
    else{
        $page = $_GET['page'];
    }

    $this_page_first_result = ($page*3)-3;

    $query = "SELECT * FROM btstpeca WHERE concluida=false ORDER BY $campo $ord LIMIT " . $results_per_page . " OFFSET '$this_page_first_result' ";
    $result = pg_query($connection, $query);

    if(pg_affected_rows($result)>0){
        for ($i=0; $i<pg_affected_rows($result); $i++){
            $array = pg_fetch_array($result);
            $date = $array['prazoentrega'];
            $cod = $array['codigo'];    //codigo filme tabela
            $datahoje = date('Y-m-d');
    ?>

    <img src="upload/<?php echo $array['imagem']?>" height="120" width="80" alt="img">

    <p><?php echo $array['codigo']; ?></p>
    <p><?php echo $array['nome']; ?></p>
    <?php
    if($datahoje<=$date){
        echo "<p class='dataverde'>" . $date . "</p>";
    }
    if($datahoje>$date){
        echo "<p class='datavermelha'>" . $date . "</p>";
    }
    ?>

    <p><a href="detalhes.php?cod=<?php echo $cod?>"> <span> Detalhes </span></a>
        <a id="editar" href="editar.php?cod=<?php echo $cod ?>"><span> Editar </span></a>
        <br><br><a id="eliminar" href="eliminar.php?cod=<?php echo $cod ?>">Eliminar</a></p>

    <?php

    date_default_timezone_set("Europe/Lisbon");

    if(isset($_POST['submit2'])){
        $username = $_SESSION['username'];

         $data = date('Y-m-d');
         $query = "UPDATE btstpeca SET concluida=true, dataconclusao='$data', btsttrabalhador_username1='$username' WHERE codigo='$cod'";
         $result = pg_query($connection, $query);
    }
    ?>

    <form action="menu.php" method="post">
        <a href="menu.php?cod=<?php echo $cod ?>">
            <input type="submit" name="submit2" value="Concluir" id="concluir">
        </a>
    </form>
    <?php }} ?>
</div>

<div class="paginas">
    <?php
    echo "<a href='?page=1'>".'|<'."</a> "; // Goto 1st page

    for ($i = max(1, $page - 5); $i <= min($page + 5, $number_of_pages); $i++) {
        echo "<a href='?page=".$i."'>".$i."</a> ";
    }
    echo "<a href='?page=$number_of_pages'>".'>|'."</a> "; // Goto last page
    ?>
</div>
</body>
</html>

