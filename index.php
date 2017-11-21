<?php
session_start();
require './_app/config.php';
$errado = FALSE;
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($post['entrar']) && !empty($post['entrar'])):
    $email = addslashes($post['email']);
    $senha = md5(addslashes($post['password']));

    $query = "SELECT * FROM militar_estadual WHERE email = :email AND senha = :senha";
    $sql = $pdo->prepare($query);
    $sql->bindValue(":email", $email);
    $sql->bindValue(":senha", $senha);
    $sql->execute();

    if ($sql->rowCount() > 0):
        $data = $sql->fetch();

        $_SESSION['id'] = $data['id_militar_estadual'];
        $_SESSION['nome'] = $data['nome'];
        $_SESSION['privilegio'] = $data['privilegio'];
        $_SESSION['status'] = $data['status'];

        if ($data['privilegio'] == 1 && $data['status'] == 'ativo'):
            header("Location: _system/indexPrivView.php");
        elseif ($data['privilegio'] == 2 && $data['status'] == 'ativo'):
            header("Location: _system/index.php");
        else:
            header("Location: login.php");
        endif;

    else:
        $errado = TRUE;
    endif;
endif;
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap/bootstrap.min.css" />
        <script type="text/javascript" src="bootstrap/jquery.min.js"></script>
        <script type="text/javascript" src="bootstrap/bootstrap.min.js"></script>
        <link rel="shortcut icon" href="css/faviconpm.ico" />
    </head>
    <body style="padding: 0" >
        <div  class="container" style="text-align: center; padding-top: 2%; margin-top: 2%;  align-content: center; background-color: rgba(0,0,255,0.2);">
            <div>
                <img width="100%" src="css/cabeçalho.png">
                <!--            <div style="background-color: #ccc">
                                <h3>Sistema de Valorização Profissional<small></small></h3>
                                    <p>Sistema de Gestão de Pontos dos Policiais Militares</p>
                            </div>-->
            </div>
            <h1 class="jumbotron" style="font-family: monospace; padding: 0; color: #09f;font-size: 1.5em;" >Sistema de Valorização Profissional</h1>
            <div class="row">

                <div style="width: 30%; float: left; margin: 6% 35% 16% 35%;">

                    <form method="post" class="panel panel-title" style="padding: 10%; background-color: rgba(0,155,0,0.2); border-radius: 10%">
                        <label  style="width: 90%">E-mail:</label>
                        <input type="email" name="email" style="width: 90%" required/></br></br>

                        <label  style="width: 90%">Senha:</label>
                        <input type="password" name="password" style="width: 90%" required/></br></br>

                        <input class="btn btn-primary"type="submit" value="Entrar" name="entrar" />
                        <a href="emailCadUsuario.php">Cadastrar</a>
                    </form>
                    <?php
                    if (($errado) && $post['entrar']):
                        echo '<div class = "alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        email e/ou senha incorretos!!
                    </div>';
                    endif;
                    unset($post);
                    ?>
                </div>
            </div>
            <?php
            require './rodape.php';
            ?>
    </body>
</html>
