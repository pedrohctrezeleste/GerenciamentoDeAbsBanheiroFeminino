
<?php

session_start();

include_once("./conexao.php");


/* =========================================
   FILTROS
========================================= */

$data_inicio = $_GET['data_inicio'] ?? '';
$data_fim    = $_GET['data_fim'] ?? '';
$andar       = $_GET['andar'] ?? '';
$tipo        = $_GET['tipo'] ?? '';


/* =========================================
   CONSULTA
========================================= */

$sql = "
    SELECT
        id_historico,
        andar,
        tipo,
        quantidade_movimentada,
        quantidade_anterior,
        quantidade_nova,
        data_hora
    FROM historico
    WHERE 1=1
";


/* FILTRO DATA INICIAL */

if ($data_inicio != '') {

    $sql .= "
        AND DATE(data_hora) >= '$data_inicio'
    ";

}


/* FILTRO DATA FINAL */

if ($data_fim != '') {

    $sql .= "
        AND DATE(data_hora) <= '$data_fim'
    ";

}


/* FILTRO ANDAR */

if ($andar != '') {

    $sql .= "
        AND andar = '$andar'
    ";

}


/* FILTRO TIPO */

if ($tipo != '') {

    $sql .= "
        AND tipo = '$tipo'
    ";

}


/* ORDEM */

$sql .= "
    ORDER BY data_hora DESC
";


$resultado = mysqli_query($conn, $sql);


if (!$resultado) {

    die("Erro na consulta: " . mysqli_error($conn));

}

?>


<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Relatório de Movimentações</title>


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- CSS principal -->

    <link
        rel="stylesheet"
        href="./css/style.css"
    >


    <style>

        /* =========================================================
           RELATÓRIO
        ========================================================= */

        .relatorio-container {

            width: 100%;
            max-width: 1200px;
            margin: 0 auto;

        }


        /* =========================================================
           CABEÇALHO
        ========================================================= */

        .relatorio-header {

            text-align: center;
            margin-bottom: 30px;

        }


        .relatorio-header h1 {

            margin-bottom: 8px;

        }


        .relatorio-header p {

            margin-bottom: 0;

        }


        /* =========================================================
           CARD DE FILTROS
        ========================================================= */

        .filtros-card {

            background: #ffffffcc;

            backdrop-filter: blur(6px);

            border-radius: 20px;

            padding: 30px;

            box-shadow:
                0 10px 30px
                rgba(214, 51, 108, 0.18);

            margin-bottom: 25px;

        }


        .filtros-card form {

            background: none;

            backdrop-filter: none;

            padding: 0;

            box-shadow: none;

            max-width: none;

            width: 100%;

            margin: 0;

        }


        .filtro-grupo {

            display: flex;

            flex-direction: column;

        }


        .filtro-grupo label {

            font-weight: 600;

            color: #ad4d80;

            margin-bottom: 7px;

            font-size: 0.95rem;

        }


        .filtro-input,
        .filtro-select {

            width: 100%;

            padding: 12px 14px;

            border: 2px solid #f7c6dd;

            border-radius: 12px;

            font-family: 'Poppins', sans-serif;

            font-size: 0.95rem;

            color: #5a3350;

            background: #fff8fb;

            outline: none;

            transition:
                border-color 0.25s ease,
                box-shadow 0.25s ease;

        }


        .filtro-input:focus,
        .filtro-select:focus {

            border-color: #cc5de8;

            box-shadow:
                0 0 0 3px
                rgba(204, 93, 232, 0.2);

        }


        /* =========================================================
           BOTÕES
        ========================================================= */

        .botoes-filtro {

            display: flex;

            justify-content: center;

            align-items: center;

            gap: 12px;

            margin-top: 22px;

            flex-wrap: wrap;

        }


        .botao-relatorio {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            font-family: 'Poppins', sans-serif;

            font-size: 0.95rem;

            font-weight: 600;

            color: #ffffff;

            background:
                linear-gradient(
                    135deg,
                    #f783ac,
                    #cc5de8
                );

            border: none;

            padding: 12px 28px;

            border-radius: 30px;

            cursor: pointer;

            text-decoration: none;

            box-shadow:
                0 6px 15px
                rgba(214, 51, 108, 0.35);

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                background 0.25s ease;

        }


        .botao-relatorio:hover {

            color: #ffffff;

            transform: translateY(-3px);

            box-shadow:
                0 10px 20px
                rgba(214, 51, 108, 0.45);

            background:
                linear-gradient(
                    135deg,
                    #e64980,
                    #be4bdb
                );

        }


        .botao-limpar {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            font-family: 'Poppins', sans-serif;

            font-size: 0.95rem;

            font-weight: 600;

            color: #ad4d80;

            background: #fff8fb;

            border: 2px solid #f7c6dd;

            padding: 10px 26px;

            border-radius: 30px;

            text-decoration: none;

            transition:
                transform 0.25s ease,
                background 0.25s ease,
                color 0.25s ease;

        }


        .botao-limpar:hover {

            color: #d6336c;

            background: #ffe3ea;

            transform: translateY(-2px);

        }


        /* =========================================================
           TABELA
        ========================================================= */

        .tabela-card {

            background: #ffffffcc;

            backdrop-filter: blur(6px);

            border-radius: 20px;

            padding: 22px;

            box-shadow:
                0 10px 30px
                rgba(214, 51, 108, 0.18);

            overflow: hidden;

        }


        .tabela-titulo {

            font-family: 'Playfair Display', serif;

            color: #ad4d80;

            font-size: 1.35rem;

            margin-bottom: 18px;

            text-align: left;

        }


        .tabela-wrapper {

            width: 100%;

            overflow-x: auto;

            border-radius: 14px;

        }


        .tabela-relatorio {

            width: 100%;

            min-width: 800px;

            border-collapse: separate;

            border-spacing: 0;

            overflow: hidden;

        }


        .tabela-relatorio thead th {

            background:
                linear-gradient(
                    135deg,
                    #f783ac,
                    #cc5de8
                );

            color: #ffffff;

            padding: 13px 12px;

            font-size: 0.88rem;

            font-weight: 600;

            text-align: center;

            border: none;

            white-space: nowrap;

        }


        .tabela-relatorio thead th:first-child {

            border-top-left-radius: 10px;

        }


        .tabela-relatorio thead th:last-child {

            border-top-right-radius: 10px;

        }


        .tabela-relatorio tbody td {

            padding: 12px;

            text-align: center;

            color: #5a3350;

            font-size: 0.9rem;

            background: rgba(255, 248, 251, 0.85);

            border-bottom: 1px solid #f7c6dd;

        }


        .tabela-relatorio tbody tr {

            transition: background 0.2s ease;

        }


        .tabela-relatorio tbody tr:hover td {

            background: #ffeaf2;

        }


        .tabela-vazia {

            padding: 25px !important;

            color: #8a5a7a !important;

            font-style: italic;

        }


        /* =========================================================
           BADGES
        ========================================================= */

        .badge-entrada,
        .badge-retirada,
        .badge-atualizacao {

            display: inline-block;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 0.75rem;

            font-weight: 700;

            letter-spacing: 0.3px;

        }


        .badge-entrada {

            color: #087f5b;

            background: #d3f9d8;

        }


        .badge-retirada {

            color: #c2255c;

            background: #ffe3ea;

        }


        .badge-atualizacao {

            color: #862e9c;

            background: #f3d9fa;

        }


        /* =========================================================
           RESUMO
        ========================================================= */

        .resumo {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;

            margin-top: 25px;

        }


        .resumo-card {

            background: #ffffffcc;

            backdrop-filter: blur(6px);

            border-radius: 18px;

            padding: 22px;

            text-align: center;

            box-shadow:
                0 8px 22px
                rgba(214, 51, 108, 0.16);

        }


        .resumo-card i {

            font-size: 1.8rem;

            display: block;

            margin-bottom: 7px;

        }


        .resumo-card strong {

            display: block;

            font-size: 0.85rem;

            letter-spacing: 0.5px;

            margin-bottom: 5px;

        }


        .resumo-numero {

            font-size: 2rem;

            font-weight: 700;

            display: block;

        }


        .resumo-label {

            font-size: 0.85rem;

            color: #8a5a7a;

        }


        .resumo-entrada {

            color: #087f5b;

        }


        .resumo-retirada {

            color: #c2255c;

        }


        .resumo-atualizacao {

            color: #862e9c;

        }


        /* =========================================================
           BOTÃO VOLTAR
        ========================================================= */

        .voltar-relatorio {

            text-align: center;

            margin: 30px 0 10px;

        }


        /* =========================================================
           RESPONSIVO
        ========================================================= */

        @media (max-width: 800px) {

            .resumo {

                grid-template-columns: 1fr;

            }

        }


        @media (max-width: 600px) {

            .filtros-card {

                padding: 22px 18px;

            }


            .tabela-card {

                padding: 15px;

            }


            .relatorio-header h1 {

                font-size: 1.8rem;

            }


            .botoes-filtro {

                flex-direction: column;

            }


            .botao-relatorio,
            .botao-limpar {

                width: 100%;

            }

        }

    </style>

</head>


<body>


<div class="relatorio-container">


    <!-- =====================================================
         CABEÇALHO
    ====================================================== -->

    <div class="relatorio-header">

        <h1>

            <i class="bi bi-clipboard-data"></i>

            RELATÓRIO DE MOVIMENTAÇÕES

        </h1>

        <p>

            Controle de entradas, retiradas e atualizações de absorventes

        </p>

    </div>



    <!-- =====================================================
         FILTROS
    ====================================================== -->

    <div class="filtros-card">

        <form
            method="GET"
            action="relatorio.php"
        >

            <div class="row g-3">


                <!-- DATA INICIAL -->

                <div class="col-md-3 filtro-grupo">

                    <label for="data_inicio">

                        Data inicial

                    </label>

                    <input
                        type="date"
                        name="data_inicio"
                        id="data_inicio"
                        class="filtro-input"
                        value="<?php echo htmlspecialchars($data_inicio); ?>"
                    >

                </div>


                <!-- DATA FINAL -->

                <div class="col-md-3 filtro-grupo">

                    <label for="data_fim">

                        Data final

                    </label>

                    <input
                        type="date"
                        name="data_fim"
                        id="data_fim"
                        class="filtro-input"
                        value="<?php echo htmlspecialchars($data_fim); ?>"
                    >

                </div>


                <!-- ANDAR -->

                <div class="col-md-3 filtro-grupo">

                    <label for="andar">

                        Andar

                    </label>

                    <select
                        name="andar"
                        id="andar"
                        class="filtro-select"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="Térreo"
                            <?php if ($andar == 'Térreo') echo 'selected'; ?>
                        >
                            Térreo
                        </option>

                        <option
                            value="1º Andar"
                            <?php if ($andar == '1º Andar') echo 'selected'; ?>
                        >
                            1º Andar
                        </option>

                        <option
                            value="2º Andar"
                            <?php if ($andar == '2º Andar') echo 'selected'; ?>
                        >
                            2º Andar
                        </option>

                        <option
                            value="3º Andar"
                            <?php if ($andar == '3º Andar') echo 'selected'; ?>
                        >
                            3º Andar
                        </option>

                        <option
                            value="4º Andar"
                            <?php if ($andar == '4º Andar') echo 'selected'; ?>
                        >
                            4º Andar
                        </option>

                    </select>

                </div>


                <!-- TIPO -->

                <div class="col-md-3 filtro-grupo">

                    <label for="tipo">

                        Movimentação

                    </label>

                    <select
                        name="tipo"
                        id="tipo"
                        class="filtro-select"
                    >

                        <option value="">
                            Todas
                        </option>

                        <option
                            value="ENTRADA"
                            <?php if ($tipo == 'ENTRADA') echo 'selected'; ?>
                        >
                            Entrada
                        </option>

                        <option
                            value="RETIRADA"
                            <?php if ($tipo == 'RETIRADA') echo 'selected'; ?>
                        >
                            Retirada
                        </option>

                        

                    </select>

                </div>


            </div>


            <!-- BOTÕES -->

            <div class="botoes-filtro">

                <button
                    type="submit"
                    class="botao-relatorio"
                >

                    <i class="bi bi-search"></i>

                    FILTRAR

                </button>


                <a
                    href="relatorio.php"
                    class="botao-limpar"
                >

                    <i class="bi bi-x-circle"></i>

                    LIMPAR

                </a>

            </div>

        </form>

    </div>



    <!-- =====================================================
         TABELA
    ====================================================== -->

    <div class="tabela-card">

        <div class="tabela-titulo">

            <i class="bi bi-clock-history"></i>

            Histórico de movimentações

        </div>


        <div class="tabela-wrapper">

            <table class="tabela-relatorio">

                <thead>

                    <tr>

                        <th>
                            Data
                        </th>

                        <th>
                            Hora
                        </th>

                        <th>
                            Andar
                        </th>

                        <th>
                            Tipo
                        </th>

                        <th>
                            Quantidade
                        </th>

                        <th>
                            Estoque anterior
                        </th>

                        <th>
                            Estoque atual
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php

                $total_entrada = 0;

                $total_retirada = 0;

                $total_atualizacao = 0;


                if (mysqli_num_rows($resultado) > 0) {

                    while ($row = mysqli_fetch_assoc($resultado)) {


                        $data = date(
                            'd/m/Y',
                            strtotime($row['data_hora'])
                        );


                        $hora = date(
                            'H:i',
                            strtotime($row['data_hora'])
                        );


                        /* TOTAL ENTRADAS */

                        if ($row['tipo'] == 'ENTRADA') {

                            $total_entrada +=
                                (int) $row['quantidade_movimentada'];

                        }


                        /* TOTAL RETIRADAS */

                        if ($row['tipo'] == 'RETIRADA') {

                            $total_retirada +=
                                (int) $row['quantidade_movimentada'];

                        }


                        /* TOTAL ATUALIZAÇÕES */

                        if ($row['tipo'] == 'ATUALIZAÇÃO') {

                            $total_atualizacao +=
                                (int) $row['quantidade_movimentada'];

                        }

                ?>

                    <tr>


                        <!-- DATA -->

                        <td>

                            <?php echo $data; ?>

                        </td>


                        <!-- HORA -->

                        <td>

                            <?php echo $hora; ?>

                        </td>


                        <!-- ANDAR -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['andar']
                            );

                            ?>

                        </td>


                        <!-- TIPO -->

                        <td>

                            <?php

                            if ($row['tipo'] == 'ENTRADA') {

                                echo '
                                    <span class="badge-entrada">
                                        ENTRADA
                                    </span>
                                ';

                            } elseif ($row['tipo'] == 'RETIRADA') {

                                echo '
                                    <span class="badge-retirada">
                                        RETIRADA
                                    </span>
                                ';

                            } elseif ($row['tipo'] == 'ATUALIZAÇÃO') {

                                echo '
                                    <span class="badge-atualizacao">
                                        ATUALIZAÇÃO
                                    </span>
                                ';

                            }

                            ?>

                        </td>


                        <!-- QUANTIDADE -->

                        <td>

                            <?php

                            echo (int)
                                $row['quantidade_movimentada'];

                            ?>

                        </td>


                        <!-- ESTOQUE ANTERIOR -->

                        <td>

                            <?php

                            echo (int)
                                $row['quantidade_anterior'];

                            ?>

                        </td>


                        <!-- ESTOQUE ATUAL -->

                        <td>

                            <?php

                            echo (int)
                                $row['quantidade_nova'];

                            ?>

                        </td>


                    </tr>


                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td
                            colspan="7"
                            class="tabela-vazia"
                        >

                            <i class="bi bi-info-circle"></i>

                            Nenhuma movimentação encontrada.

                        </td>

                    </tr>

                <?php

                }

                ?>


                </tbody>

            </table>

        </div>

    </div>



    <!-- =====================================================
         RESUMO
    ====================================================== -->

    <div class="resumo">


        <!-- ENTRADAS -->

        <div class="resumo-card resumo-entrada">

            <i class="bi bi-box-arrow-in-down"></i>

            <strong>

                TOTAL DE ENTRADAS

            </strong>

            <span class="resumo-numero">

                <?php echo $total_entrada; ?>

            </span>

            <span class="resumo-label">

                absorventes

            </span>

        </div>


        <!-- RETIRADAS -->

        <div class="resumo-card resumo-retirada">

            <i class="bi bi-box-arrow-up"></i>

            <strong>

                TOTAL DE RETIRADAS

            </strong>

            <span class="resumo-numero">

                <?php echo $total_retirada; ?>

            </span>

            <span class="resumo-label">

                absorventes

            </span>

        </div>


    </div>



    <!-- =====================================================
         VOLTAR
    ====================================================== -->

    <div class="voltar-relatorio">

        <a
            href="painel_admin.php"
            class="botao-relatorio"
        >

            <i class="bi bi-arrow-left"></i>

            VOLTAR

        </a>

    </div>


</div>


</body>

</html>

