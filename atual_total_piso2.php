
<?php

session_start();

include_once("conexao.php");


/* =========================================
   VERIFICAR SE O CAMPO FOI ENVIADO
========================================= */

if (!isset($_POST['estoque'])) {

    die("O campo estoque não foi enviado.");

}


$ESTOQUE = (int) $_POST['estoque'];


/* =========================================
   VALIDAR QUANTIDADE
========================================= */

if ($ESTOQUE < 0) {

    $_SESSION['msg'] = "Informe uma quantidade válida.";

    header("Location: painel_admin.php");

    exit;

}


/* =========================================
   BUSCAR ESTOQUE ATUAL
========================================= */

$sql_estoque = "
    SELECT estoque
    FROM piso2
    WHERE id = 1
";


$resultado_estoque = mysqli_query(
    $conn,
    $sql_estoque
);


if (!$resultado_estoque) {

    die(
        "Erro ao consultar estoque: "
        . mysqli_error($conn)
    );

}


$dados_estoque = mysqli_fetch_assoc(
    $resultado_estoque
);


$estoque_anterior =
    (int) $dados_estoque['estoque'];


/* =========================================
   CALCULAR QUANTIDADE MOVIMENTADA
========================================= */

$quantidade_movimentada =
    abs($ESTOQUE - $estoque_anterior);


/* =========================================
   ATUALIZAR ESTOQUE TOTAL
========================================= */

$sql = "
    UPDATE piso2

    SET estoque = $ESTOQUE

    WHERE id = 1
";


$resultado = mysqli_query(
    $conn,
    $sql
);


if (!$resultado) {

    die(
        "Erro no SQL: "
        . mysqli_error($conn)
    );

}


/* =========================================
   REGISTRAR NO HISTÓRICO
========================================= */

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
        '2º Andar',
        'ATUALIZAÇÃO',
        $quantidade_movimentada,
        $estoque_anterior,
        $ESTOQUE
    )
";


$resultado_historico = mysqli_query(
    $conn,
    $sql_historico
);


if (!$resultado_historico) {

    die(
        "Estoque atualizado, mas ocorreu um erro "
        . "ao registrar o histórico: "
        . mysqli_error($conn)
    );

}


/* =========================================
   MENSAGEM
========================================= */

$_SESSION['msg'] =
    "ESTOQUE ATUALIZADO COM SUCESSO!";


/* =========================================
   VOLTAR
========================================= */

header("Location: painel_admin.php");

exit;

?>
