
<?php

include("conexao.php");


/* =========================================
   RETIRAR ABSORVENTE
========================================= */

if (isset($_POST["retirar"])) {

    /* =========================================
       BUSCAR ESTOQUE ATUAL
    ========================================= */

    $sql_estoque = "SELECT estoque FROM piso3 WHERE id = 1";

    $resultado_estoque = mysqli_query($conn, $sql_estoque);

    $dados_estoque = mysqli_fetch_assoc($resultado_estoque);

    $estoque_anterior = (int) $dados_estoque["estoque"];


    /* =========================================
       VERIFICAR SE EXISTE ESTOQUE
    ========================================= */

    if ($estoque_anterior > 0) {

        /* Novo estoque após a retirada */

        $estoque_novo = $estoque_anterior - 1;


        /* =========================================
           ATUALIZAR ESTOQUE
        ========================================= */

        $sql_update = "
            UPDATE piso3
            SET estoque = $estoque_novo
            WHERE id = 1
        ";

        $resultado_update = mysqli_query($conn, $sql_update);


        /* =========================================
           REGISTRAR NO HISTÓRICO
        ========================================= */

        if ($resultado_update) {

            $sql_historico = "
                INSERT INTO historico
                (
                    andar,
                    tipo,
                    quantidade_movimentada,
                    quantidade_anterior,
                    quantidade_nova
                )
                VALUES
                (
                    '3º Andar',
                    'RETIRADA',
                    1,
                    $estoque_anterior,
                    $estoque_novo
                )
            ";

            mysqli_query($conn, $sql_historico);
        }
    }
}


/* =========================================
   CONSULTAR ESTOQUE ATUAL
========================================= */

$sql = "SELECT * FROM piso3 WHERE id = 1";

$resultado = mysqli_query($conn, $sql);

$dados = mysqli_fetch_assoc($resultado);

$estoque = $dados["estoque"];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Absorventes - Piso 3</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>


<body>


    <h1>Absorventes Gratuitos</h1>


    <div class="stock-card">


        <h2>Banheiro Feminino - Piso 3</h2>


        <center>
            <hr>
        </center>


        <h3>Estoque disponível</h3>


        <p>

            <span class="stock-number">

                <?php echo $estoque; ?>

            </span>

            <span class="stock-label">

                absorventes

            </span>

        </p>


        <?php if ($estoque > 0) { ?>


            <form method="POST">


                <button
                    type="submit"
                    name="retirar"
                >

                    Retirar absorvente

                </button>


            </form>


        <?php } else { ?>


            <p class="stock-out">

                <strong>
                    Estoque esgotado.
                </strong>

            </p>


        <?php } ?>


    </div>


    <a href="./index.php">

        VER TODOS OS ANDARES

    </a>


</body>

</html>
