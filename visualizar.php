<?php
session_start();
ob_start();
include_once './conexao.php';
include_once './main.log';

function logMsg( $msg, $level = 'info', $file = 'main.log' )
{
   
    $levelStr = '';
 
    // nível do log
    switch ( $level )
    {
        case 'info':
            // nível de informação
            $levelStr = 'INFO';
            break;
 
        case 'warning':
            // nível de aviso
            $levelStr = 'WARNING';
            break;
 
        case 'error':
            // nível de erro
            $levelStr = 'ERROR';
            break;
    }
 
    // data atual
    $date = date( 'Y-m-d H:i:s' );
 
    $msg = sprintf( "[%s] [%s]: %s%s", $date, $levelStr, $msg, PHP_EOL );

    file_put_contents( $file, $msg, FILE_APPEND );
}

$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);

if (empty($cod)) {
    $_SESSION['msg'] = "<p style='color:red;'>Cadastro não encontrado!</p>";
    header("Location: exibe.php");
}

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
    <title> Informações </title>

</head>

<body class="ml-4">
    <div class="mb-4 mt-4">
        <a href="exibe.php" class='btn btn-info no-underline font-weight-bold'
            class="no-underline text-info font-weight-bold">DATABASE</a><br>
    </div>
    <div class="mb-4 text-secondary">
        <h2 class='font-monospace font-weight-bold font-italic'>INFORMAÇÕES</h2>
    </div>

    <?php

    $query_cadastro = "SELECT cod, nome, sobrenome, idade, pais FROM cadastro WHERE cod = $cod";
    $result_cadastro = $coon->prepare($query_cadastro);
    $result_cadastro->execute();

    if (($result_cadastro) and ($result_cadastro->rowCount() != 0)) {
        $row_cadastro = $result_cadastro->fetch(PDO::FETCH_ASSOC);
        extract($row_cadastro);

    
        echo "<div class='row mb-4 mt-5 g-3'>";
        echo "<p class='col-md-1 col-xs-12 font-weight-bold text-secondary'>Cod: $cod </p>";
        echo "<p class='col-md-3 col-xs-12 font-weight-bold text-secondary'>Nome: $nome </p>";
        echo "<p class='col-md-2 col-xs-12 font-weight-bold text-secondary'>Sobrenome: $sobrenome </p>";
        echo "<p class='col-md-2 col-xs-12 font-weight-bold text-secondary'>Idade: $idade </p>";
        echo "<p class='col-md-2 col-xs-12 font-weight-bold text-secondary'>País: $pais </p>";
        echo "<a href='editar.php?cod=$cod'  class='btn btn-info no-underline font-weight-bold col-md-1 col-xs-6 ml-1 mr-3 mb-2'>EDITAR</a>";
        echo "<a href='excluir.php?cod=$cod' class='btn btn-info no-underline font-weight-bold col-md-1 col-xs-6 ml-1 mr-3 mb-2'>EXCLUIR</a>";
        echo "</div>";
    } else {
        $_SESSION['msg'] = "<p style='color:red;'>Cadastro não encontrado!</p>";

        // Registrar erro no log
        $logMessage = "Cadastro não encontrado: " . print_r($cad_cadastro->errorInfo(), true);
        logMsg($logMessage, 'error');


        header("Location: exibe.php");

    }

    ?>

</body>

</html>