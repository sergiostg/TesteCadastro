<?php
session_start();
include_once './conexao.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        .no-underline:hover {
            text-decoration: none;
        }
    </style>
    <title>Cadastrar</title>
</head>

<body class="ml-2">
    <div class="mb-4 mt-4">
        <a href="cadastrar.php" class='btn btn-info no-underline font-weight-bold'
            class="no-underline text-info font-weight-bold">CADASTRAR</a>
    </div>
    <div class="text-secondary">
        <h2 class='font-monospace font-weight-bold font-italic p-2'>Database</h2>
    </div>
    <div>
        <?php

        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }

        $pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
        $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

        // limite de cadastros por página;
        $limite_resultado = 6;

        // calc visualização;
        $inicio = ($limite_resultado * $pagina - $limite_resultado);

        $query_cadastro = "SELECT cod, nome, sobrenome, idade, pais FROM cadastro ORDER BY cod DESC LIMIT $inicio, $limite_resultado";
        $result_cadastro = $coon->prepare($query_cadastro);
        $result_cadastro->execute();

        if (($result_cadastro) and ($result_cadastro->rowCount() != 0)) {


            while ($row_cadastro = $result_cadastro->fetch(PDO::FETCH_ASSOC)) {
                extract($row_cadastro);
                echo "<div class='row mr-2 ml-2 mb-4 mt-4 g-3'>";
                echo "<p class='col-md-1 col-xs-12 font-weight-bold text-secondary'>Cod: $cod </p>";
                echo "<p class='col-md-3 col-xs-12 font-weight-bold text-secondary'>Nome: $nome </p>";
                echo "<p class='col-md-3 col-xs-12 font-weight-bold text-secondary'>Sobrenome: $sobrenome </p>";
                echo "<p class='col-md-3 col-xs-12 font-weight-bold text-secondary'>Idade: $idade </p>";
                echo "<p class='col-md-3 col-xs-12 font-weight-bold text-secondary'>País: $pais </p>";
                echo "<a href='visualizar.php?cod=$cod' class='btn btn-info no-underline font-weight-bold col-md-1 col-xs-12'>EXPANDIR</a>";
                echo "</div>";
                echo "<hr>";
            }

            // Contar a quantidade de itens;
            $query_qnt_itens = "SELECT COUNT(cod) AS num_result FROM cadastro";
            $result_qnt_itens = $coon->prepare($query_qnt_itens);
            $result_qnt_itens->execute();
            $row_qnt_itens = $result_qnt_itens->fetch(PDO::FETCH_ASSOC);

            // Contar a quantidade de páginas;
            $qnt_pagina = ceil($row_qnt_itens['num_result'] / $limite_resultado);

            // Imprimindo os dados no database;
            echo "<div class='row col-md-7 col-xs-12 justify-content-md-center mt-5 mb-5'>";
            echo "<a href='exibe.php?page=1' class='btn btn-info no-underline font-weight-bold ml-2 mr-2'> < </a>";

            // máximo de link;
            $max_link = 2;

            // validação para as páginas anteriores a página que está ativa;
            for ($pagina_anterior = $pagina - $max_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++) {
                if ($pagina_anterior >= 1) {
                    echo "<a href='exibe.php?page=$pagina_anterior' class='btn btn-info no-underline font-weight-bold ml-2 mr-2'> $pagina_anterior </a>";
                }
            }

            echo "<a href='#' class='btn btn-secondary no-underline font-weight-bold ml-2 mr-2'> $pagina </a>";

            for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $max_link; $proxima_pagina++) {
                if ($proxima_pagina <= $qnt_pagina) {
                    echo "<a href='exibe.php?page=$proxima_pagina' class='btn btn-info no-underline font-weight-bold ml-2 mr-2'> $proxima_pagina </a>";
                }
            }

            echo "<a href='exibe.php?page=$qnt_pagina' class='btn btn-info no-underline font-weight-bold ml-2 mr-2'> > </a>";
            echo "</div>";

        } else {
            echo "<p style='color:red;'> Usuario não encontrado.</p>";
        }
        ?>
    </div>
</body>

</html>