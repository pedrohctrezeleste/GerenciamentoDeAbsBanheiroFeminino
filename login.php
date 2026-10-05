<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


include("conexao.php");
session_start();

include("conexao.php");

if (isset($_POST["entrar"])) {

    $nome = $_POST["nome"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM admin 
            WHERE nome = '$nome' 
            AND senha = '$senha'";

    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) > 0) {

        $admin = mysqli_fetch_assoc($resultado);

        $_SESSION["admin_id"] = $admin["id"];
        $_SESSION["admin_nome"] = $admin["nome"];

        header("Location: painel_admin.php");
        exit;

    } else {

        echo "Nome ou senha incorretos.";

    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Login do Administrador</title>

</head>

<body>

    <h2>Login do Administrador</h2>

    <form method="POST">

        <label>Nome:</label>
        <br>

        <input type="text" name="nome" required>

        <br><br>

        <label>Senha:</label>
        <br>

        <input type="password" name="senha" required>

        <br><br>

        <button type="submit" name="entrar">
            Entrar
        </button>

    </form>

    <br>

    <a href="index.php">Voltar</a>

</body>

</html>
