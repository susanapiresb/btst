<?php
$str = "dbname=heroku_8a45ef478b17676 user=b0cde06899dea0 password=5c6335ce host=eu-cdbr-west-03.cleardb.net";
$connection = pg_connect($str);

if(isset($_POST['entrar'])) {
    session_start();

    $username = $_POST ['username'];
    $password = $_POST ['password'];

    $query = "SELECT * FROM btsttrabalhador WHERE username='$username' AND password='$password'";
    $resultado = pg_query($connection, $query);

    if (pg_affected_rows($resultado) == 1) {    //há um cliente na base de dados
        $_SESSION['username'] = $username;

        header('location: menu.php');
    }

    else {
        $erro = "Username ou password incorretos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BTST</title>
    <link type="text/css" rel="stylesheet" href="../BTST/css/index.css">
</head>
<body>

<div id="div">
    <h1>BTST</h1>

    <div>
        <h2>Login</h2>

        <form action="index.php" method="POST">
            <h3 id="username">Username</h3>
            <label>
                <input id="input" type="text" name="username" placeholder="username" required>
            </label>

            <h3 id="password">Password</h3>
            <label>
                <input id="input" type="password" name="password" placeholder="******" required>
            </label>
            <br>
            <?php if(isset($erro)){?>
                <p id="erro"><?php echo $erro;?></p>
            <?php }?>
            <label>
                <input id="entrar" type="submit" name="entrar" value="Entrar">
            </label>
        </form>
    </div>
</div>
</body>
</html>
