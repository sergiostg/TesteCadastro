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
var_dump($cod);

if (empty($cod)) {
    $_SESSION['msg'] = "<p style='color:red;' class='font-weight-bold font-italic font-monospace'> Cadastro não encontrado!</p>";
    header("Location: exibe.php");
}

$query_cadastro = "SELECT cod FROM cadastro WHERE cod = $cod LIMIT 1";
$result_cadastro = $coon->prepare($query_cadastro);
$result_cadastro->execute();

if (($result_cadastro) and ($result_cadastro->rowCount() != 0)) {
    $query_del_cadastro = "DELETE FROM cadastro WHERE cod = $cod";
    $deletar_cadastro = $coon->prepare($query_del_cadastro);
    $deletar_cadastro->execute();

    if ($deletar_cadastro->execute()) {
        $_SESSION['msg'] = "<p style='color:green;' class='font-weight-bold font-italic font-monospace'> Cadastro apagado com sucesso</p>";

        // Registrar sucesso no log
        $logMessage = "Cadastro excluido com sucesso: " . $dados['nome'] . " " . $dados['sobrenome'] . " " . $dados['idade'] . " " . $dados['pais'];
        logMsg($logMessage, 'info');

        header("Location: exibe.php");
    } else {
        $_SESSION['msg'] = "<p style='color:red;' class='font-weight-bold font-italic font-monospace'> Cadastro não foi apagado!</p>";

        // Registrar erro no log
        $logMessage = "Erro na exclusão do cadastro: " . print_r($cad_cadastro->errorInfo(), true);
        logMsg($logMessage, 'error');

        header("Location: exibe.php");
    }

} else {
    $_SESSION['msg'] = "<p style='color:red;' class='font-weight-bold font-italic font-monospace'> Cadastro não encontrado!</p>";
    header("Location: exibe.php");
}