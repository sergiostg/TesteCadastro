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

<body class="ml-2 mr-2">
    <container class="container justify-content-md-center col-md-3 col-xs-12">
        <div class="container row mb-4 mt-4">
            <div class="mr-2">
                <a href="exibe.php" class='btn btn-info no-underline font-weight-bold'>DATABASE</a>
            </div>
            <div class="ml-2">
                <a href="inicial.php" class='btn btn-info no-underline font-weight-bold'>LOGOUT</a>
            </div>
        </div>
        <div class="mb-5">
            <p class="font-italic font-monospace text-secondary">Api de validação de cadastro</p>
            <h3 class="font-monospace font-weight-bold font-italic text-secondary display-6 p-4">Cadastro</h3>
        </div>
        <?php

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($dados['Cadastrar'])) {

            $query_cadastro = "INSERT INTO cadastro (nome, sobrenome,idade,pais) VALUES (:nome, :sobrenome, :idade, :pais) ";
            $cad_cadastro = $coon->prepare($query_cadastro);
            $cad_cadastro->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
            $cad_cadastro->bindParam(':sobrenome', $dados['sobrenome'], PDO::PARAM_STR);
            $cad_cadastro->bindParam(':idade', $dados['idade'], PDO::PARAM_STR);
            $cad_cadastro->bindParam(':pais', $dados['pais'], PDO::PARAM_STR);
            $cad_cadastro->execute();

            if ($cad_cadastro->rowCount()) {
                $_SESSION['msg'] = "<p style='color:green;' class='font-weight-bold font-italic font-monospace'>Cadastrado realizado com sucesso!</p>";

                // Registrar sucesso no log
                $logMessage = "Cadastro realizado com sucesso para: " . $dados['nome'] . " " . $dados['sobrenome'] . " " . $dados['idade'] . " " . $dados['pais'];
                logMsg($logMessage, 'info');

                header("Location: exibe.php");
            } else {
                echo "<p style='color:red;' class='font-weight-bold font-italic font-monospace'>Cadastro não realizado!.</p>";

                // Registrar erro no log
                $logMessage = "Erro ao cadastrar no banco de dados: " . print_r($cad_cadastro->errorInfo(), true);
                logMsg($logMessage, 'error');
            }
        }

        ?>
        <form name="cadastro" method="POST" action="">
            <div class="row g-3">
                <div class="col-md-3 mb-1 mt-1">
                    <input type="text" name="nome" id="nome" autocomplete="off" class="form-control font-weight-bold"
                        placeholder="Nome" required>
                </div>
                <div class="col-md-2 mb-1 mt-1">
                    <input type="text" name="sobrenome" id="sobrenome" autocomplete="off"
                        class="form-control font-weight-bold" placeholder="Sobrenome" aria-label="Sobrenome" required>
                </div>
                <div class="col-md-2 mb-1 mt-1">
                    <input type="text" name="idade" id="idade" autocomplete="off" class="form-control font-weight-bold"
                        placeholder="idade" aria-label="idade" required>
                </div>
                <div class="col-md-2 mb-1 mt-1">
                    <input type="text" name="pais" id="pais" autocomplete="off" class="form-control font-weight-bold"
                        placeholder="País" aria-label="País" required>
                </div>
                <div class="col-2 mb-1 mt-1">
                    <input type="submit" class="btn btn-info no-underline font-weight-bold" value="CADASTRAR"
                        name="Cadastrar" data-bs-toggle="modal" data-bs-target="#exampleModal">
                </div>
            </div>
        </form>
    </container>
</body>

</html>

