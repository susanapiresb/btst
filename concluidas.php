<?php
session_start();
include('verifica.php');
$str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
$connection = pg_connect($str);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BTST</title>
    <link type="text/css" rel="stylesheet" href="../BTST/css/header.css">
    <link type="text/css" rel="stylesheet" href="../BTST/css/menu.css">
</head>
<body>

<header>
    <a href="menu.php">Inventário</a>
    <div class="vl"></div>
    <a href="concluidas.php">Material concluído</a>
</header>

<div class="pesquisarordenar">
    <form action="resultado2.php" method="POST">
        <label>
            <input type="text" name="pesquisar" placeholder="Nome cliente">
            <!--<input type="date" name="date" value="<?php //echo(isset($_POST['date'])) ? $_POST['date']:''; ?>" placeholder="Pesquisar por cliente ou prazo">-->
        </label>
        <input type="submit" name="submit" value="Pesquisar" id="pesquisar">
    </form>
    <form action="concluidas.php" method="POST">
        <label>
            <select name="ordem">
                <option>Nome cliente/Data conclusão</option>
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
    <h1>Data conclusão</h1>
    <p></p><p></p>
</div>

<?php
$campo = "dataconclusao";
$ord = "asc";
if (isset($_POST['ordem'])) {
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

<div class="grelha2">
    <?php
    $results_per_page = 3;

    //LIMIT 3 OFFSET 3. RESULTADO: 4 e 5. Aparecem 3 resultados (LIMIT) a partir do 3 (OFFSET) (sem contar com o 3).
    $query = "SELECT * FROM btstpeca WHERE concluida=true ORDER BY $campo $ord";
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

    $query = "SELECT * FROM btstpeca WHERE concluida=true ORDER BY $campo $ord LIMIT " . $results_per_page . " OFFSET '$this_page_first_result' ";
    $result = pg_query($connection, $query);

    if(pg_affected_rows($result)>0){
        for ($i=0; $i<pg_affected_rows($result); $i++){
            $array = pg_fetch_array($result);
            $date = $array['dataconclusao'];
            $cod = $array['codigo'];    //codigo filme tabela
            ?>

    <img src="../BTST/upload/<?php echo $array['imagem']?>" height="120" width="80" alt="img">
    <p> <?php echo $array['codigo']; ?> </p>
    <p> <?php echo $array['nome']; ?></p>
    <p> <?php echo $date ?>
        <br> <span id="trabalhador">por <?php echo $array['btsttrabalhador_username1'] ?></span> </p>
    <p> <a href="detalhes.php?cod=<?php echo $cod ?>"> <span> Detalhes </span></a>
        <br><br><a id="eliminar" href="eliminar.php?cod=<?php echo $cod ?>">Eliminar</a></p>

<?php
    if(isset($_POST['nconcluido'])){
        $query = "UPDATE btstpeca SET concluida=false, dataconclusao=null, btsttrabalhador_username1=null WHERE codigo='$cod'";
        $result = pg_query($connection, $query);
    }
?>

<form action="concluidas.php" method="post">
    <a href="concluidas.php?<?php echo $cod?>">
        <input id="nconcluida" type="submit" name="nconcluido" value="Não concluído">
    </a>
</form>
<?php }} ?>
</div>

<div class="paginas">
    <?php

    $max_pages = 10;
    //$total_records = mysql_num_rows($rs_result);  //number_of_results
    //$total_pages = ceil($total_records / $num_rec_per_page); //number_of_pages

    echo "<a href='?page=1'>".'|<'."</a> "; // Goto 1st page

    for ($i = max(1, $page - 5); $i <= min($page + 5, $number_of_pages); $i++) {
        echo "<a href='?page=".$i."'>".$i."</a> ";
    }
    echo "<a href='?page=$number_of_pages'>".'>|'."</a> "; // Goto last page

    /*for($page=1; $page<=$number_of_pages; $page++){
        if($number_of_pages>=3){
            echo '<a id="pag" href="menu.php?page=' . $page . '">'. $page . "...". $number_of_pages . '</a>';
        }
        else{
            echo '<a id="pag" href="menu.php?page=' . $page . '">' . "...".$page . '</a>';

        }
    }*/
    ?>
</div>

</body>
</html>