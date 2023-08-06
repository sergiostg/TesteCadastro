<?php
session_start();
ob_start();
include_once './conexao.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>.no-underline:hover {text-decoration: none;}</style>

    <title>Cadastrar</title>

</head>
    <body class="ml-2 mr-2 mt-5">
        <container class='container p-4'>
        <div class="container justify-content-md-center col-md-3 col-xs-12">
            <div class="mb-5 mt-5 container text-center">
                <h1 class="font-italic font-monospace font-weight-bold text-secondary">Acesso ao sistema</h1>
                <h3 class="font-italic font-monospace text-secondary display-6"></h3>
            </div>
            <form name="cadastro" method="POST" action="" class="" >
                    <div class="">
                        <div class="">
                            <input type="text" name="livro" id="livro" autocomplete="off" class="form-control font-weight-bold mb-5" placeholder="USUÁRIO" required >
                            <input type="text" name="autor" id="autor" autocomplete="off" class="form-control font-weight-bold" placeholder="SENHA" aria-label="Autor" required >
                        </div>
                        <div class="text-center">
                            <a href="cadastrar.php" class='btn btn-info no-underline font-weight-bold mb-5 mt-5'>ENTRAR</a>
                        </div>
                    </div>
            </form>  
        </div>
        </container>
    </body>
</html>