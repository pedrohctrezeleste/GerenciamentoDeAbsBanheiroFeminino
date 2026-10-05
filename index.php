<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Absorventes Gratuitos</title>
</head>

<body>

    <h1>ABcontrol Absorventes Gratuitos</h1>

    <p>Sistema de controle de estoque</p>
   

</body>
<?php
include_once("conexao.php");

$piso1 = mysqli_query($conn, "SELECT * FROM piso1 WHERE id = 1");
$piso2 = mysqli_query($conn, "SELECT * FROM piso2 WHERE id = 1");
$piso3 = mysqli_query($conn, "SELECT * FROM piso3 WHERE id = 1");
$piso4 = mysqli_query($conn, "SELECT * FROM piso4 WHERE id = 1");

$dados1 = mysqli_fetch_assoc($piso1);
$dados2 = mysqli_fetch_assoc($piso2);
$dados3 = mysqli_fetch_assoc($piso3);
$dados4 = mysqli_fetch_assoc($piso4);

?>

<hr>

    <h2>Estoque de Absorventes</h2>

    <div class="pisos">

        <div class="card">
            <h3>Térreo</h3>

            <p>
                Temos no Estoque:
                <br>
                <strong>
                    <?php echo $dados1["estoque"]; ?>
                </strong><br>
                Unidades
            </p>

            
        </div>

        <div class="card">
            <h3>Primeiro Andar</h3>

            <p>
               Temos no Estoque:
                <br>
                <strong>
                    <?php echo $dados2["estoque"]; ?>
                </strong><br>
                Unidades
            </p>

           
        </div>

        <div class="card">
            <h3>Segundo Andar</h3>

            <p>
                Temos no Estoque:
                <br>
                <strong>
                    <?php echo $dados3["estoque"]; ?>
                </strong><br>
                Unidades
            </p>

            
        </div>

        <div class="card">
            <h3>Terceiro Andar</h3>

            <p>
                Temos no Estoque:
                <br>
                <strong>
                    <?php echo $dados4["estoque"]; ?>
                </strong><br>
                Unidades
            </p>

            
        </div>

    </div>
    <hr>
    <a href="./farmacias.php"> <button> Consulte aqui fármacias que participam do programa Farmácia Popular</button></a>
    <hr>

    <h2>Área do Administrador</h2>

    <a href="login.php">
        <button>Acesso Administrador</button>
    </a>


    <a href="index.php">
        Voltar
    </a>
    <p>
        <a href="logout.php">
            Sair
        </a>
    </p>


</html>





