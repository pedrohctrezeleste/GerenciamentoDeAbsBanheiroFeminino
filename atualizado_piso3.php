
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

if ($ESTOQUE <= 0) {

    $_SESSION['msg'] = "Informe uma quantidade válida.";

    header("Location: painel_admin.php");

    exit;

}


/* =========================================
   BUSCAR ESTOQUE ATUAL
========================================= */

$sql_estoque = "
    SELECT estoque
    FROM piso3
    WHERE id = 1
";

$resultado_estoque = mysqli_query($conn, $sql_estoque);


if (!$resultado_estoque) {

    die("Erro ao consultar estoque: " . mysqli_error($conn));

}


$dados_estoque = mysqli_fetch_assoc($resultado_estoque);

$estoque_anterior = (int) $dados_estoque['estoque'];


/* =========================================
   CALCULAR NOVO ESTOQUE
========================================= */

$estoque_novo = $estoque_anterior + $ESTOQUE;


/* =========================================
   ATUALIZAR ESTOQUE
========================================= */

$sql = "
    UPDATE piso3

    SET estoque = $estoque_novo

    WHERE id = 1
";


$resultado = mysqli_query($conn, $sql);


if (!$resultado) {

    die("Erro no SQL: " . mysqli_error($conn));

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
        '3º Andar',
        'ENTRADA',
        $ESTOQUE,
        $estoque_anterior,
        $estoque_novo
    )
";


$resultado_historico = mysqli_query($conn, $sql_historico);


if (!$resultado_historico) {

    die(
        "Estoque atualizado, mas ocorreu um erro ao registrar o histórico: "
        . mysqli_error($conn)
    );

}


/* =========================================
   MENSAGEM
========================================= */

$_SESSION['msg'] = "ESTOQUE ATUALIZADO COM SUCESSO!";


/* =========================================
   VOLTAR
========================================= */

header("Location: painel_admin.php");

exit;

?>

