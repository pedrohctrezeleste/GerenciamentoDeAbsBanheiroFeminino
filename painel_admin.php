<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

include("conexao.php");
if(isset($_SESSION['msg'])){
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
};


$piso1 = mysqli_query($conn, "SELECT * FROM piso1 WHERE id = 1");
$piso2 = mysqli_query($conn, "SELECT * FROM piso2 WHERE id = 1");
$piso3 = mysqli_query($conn, "SELECT * FROM piso3 WHERE id = 1");
$piso4 = mysqli_query($conn, "SELECT * FROM piso4 WHERE id = 1");

$dados1 = mysqli_fetch_assoc($piso1);
$dados2 = mysqli_fetch_assoc($piso2);
$dados3 = mysqli_fetch_assoc($piso3);
$dados4 = mysqli_fetch_assoc($piso4);

$total = $dados1["estoque"]+$dados2["estoque"]+$dados3["estoque"]+$dados4["estoque"];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel Administrativo</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <h1>ABcontrol Painel Administrativo</h1>

    <p>
        Administrador:
        <strong>
            <?php echo htmlspecialchars($_SESSION["admin_nome"]); ?>
        </strong>
    </p>

    <hr>

    <h2>Estoque de Absorventes</h2>

    <div class="pisos">

        <div class="card">
            <h3>Térreo</h3>

            <p>
                Estoque:
                <br>
                <strong>
                    <?php echo $dados1["estoque"]; ?>
                </strong>
            </p>

            <a href="atualizar_piso1.php">
                <button type="button">
                    Atualizar estoque do Piso 1
                </button>
            </a>
         
            <?php if ($dados1["estoque"] < 4): ?>

<div class="alerta-estoque">
     Estoque baixo!
</div>

<?php endif; ?>


        </div>

        <div class="card">
            <h3>Primeiro Andar</h3>

            <p>
                Estoque:
                <br>
                <strong>
                    <?php echo $dados2["estoque"]; ?>
                </strong>
            </p>

            <a href="atualizar_piso2.php">
                <button type="button">
                    Atualizar estoque do Piso 2
                </button>
            </a>

            <?php if ($dados2["estoque"] < 4): ?>

            <div class="alerta-estoque">
                Estoque baixo!
            </div>

<?php endif; ?>

        </div>

        <div class="card">
            <h3>Segundo Andar</h3>

            <p>
                Estoque:
                <br>
                <strong>
                    <?php echo $dados3["estoque"]; ?>
                </strong>
            </p>

            <a href="atualizar_piso3.php">
                <button type="button">
                    Atualizar estoque do Piso 3
                </button>
            </a>
            <?php if ($dados3["estoque"] < 4): ?>

            <div class="alerta-estoque">
                Estoque baixo!
            </div>

<?php endif; ?>

        </div>

        <div class="card">
            <h3>Terceiro Andar</h3>

            <p>
                Estoque:
                <br>
                <strong>
                    <?php echo $dados4["estoque"]; ?>
                </strong>
            </p>

            <a href="atualizar_piso4.php">
                <button type="button">
                    Atualizar estoque do Piso 4
                </button>
            </a>
            <?php if ($dados4["estoque"] < 4): ?>

            <div class="alerta-estoque">
                 Estoque baixo!
            </div>

<?php endif; ?>

        </div>

                <br>

        

    </div>

    <hr>

    <p>
                TOTAL:
                <br>
                <strong>
                    <?php echo $total; ?>
                </strong>
            </p>
            <br>
   

    <p>
        <a href="relatorio.php">
            Histórico
        </a>
    </p>
    <p>
        <a href="logout.php">
            Sair
        </a>
    </p>
 <a href="index.php">
        Voltar
    </a>
</body>

</html>