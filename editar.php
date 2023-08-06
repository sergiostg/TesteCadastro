<?php
session_start();
ob_start();
include_once './conexao.php';
include_once './main.log';
$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);

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

if (empty($cod)) {
    $_SESSION['msg'] = "<p style='color:red;'>Cadastro não encontrado!</p>";
    header("Location: exibe.php");
}

$query_cadastro = "SELECT cod, nome, sobrenome, idade, pais FROM cadastro WHERE cod = $cod LIMIT 1";
$result_cadastro = $coon->prepare($query_cadastro);
$result_cadastro->execute();

if (($query_cadastro) and ($result_cadastro->rowCount() != 0)) {
    $row_cadastro = $result_cadastro->fetch(PDO::FETCH_ASSOC);
} else {
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
    <title> Editar </title>
</head>

<body class="ml-2 g-3">
    <div class="mb-4 mt-4 text-secondary">
        <h2 class='font-monospace font-weight-bold font-italic'>Editar</h2>
    </div>

    <?php

    //Receber os dados do formulário
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    // Verificar se o usuário clicou no botão
    if (!empty($dados['Editcadastro'])) {
        $empty_input = false;
        $dados = array_map('trim', $dados);
        if (in_array("", $dados)) {
            $empty_input = true;
            echo "<p style='color:red;' class='font-weight-bold font-italic'>Erro: Necessário preencher todos os campos!</p>";
        }

        if (!$empty_input) {
            $query_cadastro = "UPDATE cadastro SET nome=:nome, sobrenome=:sobrenome, idade=:idade, pais=:pais WHERE cod=:cod";
            $edit_cadastro = $coon->prepare($query_cadastro);
            $edit_cadastro->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
            $edit_cadastro->bindParam(':sobrenome', $dados['sobrenome'], PDO::PARAM_STR);
            $edit_cadastro->bindParam(':idade', $dados['idade'], PDO::PARAM_STR);
            $edit_cadastro->bindParam(':pais', $dados['pais'], PDO::PARAM_STR);
            $edit_cadastro->bindParam(':cod', $cod, PDO::PARAM_INT);
            if ($edit_cadastro->execute()) {
                $_SESSION['msg'] = "<p style='color:green;' class='font-weight-bold font-italic'>Cadastro editado com sucesso</p>";
                header("Location: exibe.php");
                
                //cadastro realizado com sucesso
                $logMessage = "Cadastro atualizado para: " . $dados['nome'] . " " . $dados['sobrenome'] . " " . $dados['idade'] . " " . $dados['pais'];
                logMsg($logMessage, 'info');

            } else {
                echo "<p style='color:red;' class='font-weight-bold font-italic'>Erro: Cadastro não editado</p>";

                // Registrar erro no log
                $logMessage = "Erro ao cadastrar no banco de dados: " . print_r($cad_cadastro->errorInfo(), true);
                logMsg($logMessage, 'error');

            }
        }
    }
    ?>

    <form name="edit-cadastro" method="POST" action="" class="">
        <div class="col-md-4 col-xs-12">
            <p class="font-weight-bold text-secondary">Nome: </p>
            <input type="text" name="nome" id="nome" placeholder="Nome" autocomplete="off"
                class="form-control font-weight-bold" value="<?php
                if (isset($dados['nome'])) {
                    echo $dados['nome'];
                } elseif (isset($row_cadastro['nome'])) {
                    echo $row_cadastro['nome'];
                } ?>"><br><br>
        </div>
        <div class="col-md-4 col-xs-12">
            <p class="font-weight-bold text-secondary">Sobrenome: </p>
            <input type="text" name="sobrenome" id="sobrenome" placeholder="Sobrenome" autocomplete="off"
                class="form-control font-weight-bold" value="<?php
                if (isset($dados['sobrenome'])) {
                    echo $dados['sobrenome'];
                } elseif (isset($row_cadastro['sobrenome'])) {
                    echo $row_cadastro['sobrenome'];
                } ?>"><br><br>
        </div>
        <div class="col-md-4 col-xs-12">
            <p class="font-weight-bold text-secondary">Idade: </p>
            <input type="text" name="idade" id="idade" placeholder="Idade" autocomplete="off"
                class="form-control font-weight-bold" value="<?php
                if (isset($dados['idade'])) {
                    echo $dados['idade'];
                } elseif (isset($row_cadastro['idade'])) {
                    echo $row_cadastro['idade'];
                } ?>"><br><br>
        </div>
        <div class="col-md-4 col-xs-12">
            <p class="font-weight-bold text-secondary">País: </p>
            <input type="text" name="pais" id="pais" placeholder="País" autocomplete="off"
                class="form-control font-weight-bold" value="<?php
                if (isset($dados['pais'])) {
                    echo $dados['pais'];
                } elseif (isset($row_cadastro['pais'])) {
                    echo $row_cadastro['pais'];
                } ?>"><br><br>
        </div>
        <div class="col-3">
            <input type="submit" value="SALVAR" class="btn btn-info no-underline font-weight-bold" name="Editcadastro">
        </div>
    </form>
</body>

</html>