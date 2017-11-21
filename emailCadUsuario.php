<!DOCTYPE html>

<html>
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap/bootstrap.min.css" />
        <script type="text/javascript" src="bootstrap/jquery.min.js"></script>
        <script type="text/javascript" src="bootstrap/bootstrap.min.js"></script>

    </head>
    <body>
        <div  class="container" style="text-align: center; padding-top: 2%; margin-top: 2%;  align-content: center; background-color: rgba(0,0,255,0.2)">
            <div>
                <img width="100%" src="css/cabeçalho.png">

            </div>
            <h1 class="jumbotron" style="font-family: monospace; padding: 0; color: #09f;font-size: 1.5em;" >Sistema de Valorização Profissional</h1>
            <div style="float: right">
                <a href="login.php">Voltar</a>
            </div>

            <div class="row">

                <div style="width: 70%; float: left; margin: 6% 15% 16% 15%;">
                    <?php
                    require './_app/config.php';
                    require './_app/Config.inc.php';
                    require './_app/Conn/Conn.class.php';
                    require './_app/Conn/Read.class.php';
                    require './_models/MilitarEstadual.class.php';

                    try {

                        $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                        if (isset($post) && $post['cadastrar']):
                            $posto = addslashes($post['posto']);
                            $nome = strtoupper(utf8_encode(addslashes($post['nome'])));
                            $sobrenome = strtoupper(utf8_encode(addslashes($post['sobrenome'])));
                            $rg = addslashes($post['rg']);
                            $nf = addslashes($post['nf']);
                            $unidade = addslashes($post['unidade']);
                            $subunidade = addslashes($post['subunidade']);
                            $email = addslashes($post['email']);
                            $senha = md5(addslashes($post['senha']));

                            $read = new MilitarEstadual();
                            if ($read->buscaME($rg) > 0):
                                extract($read->buscaME($rg));
                                echo 'Seu cadastro já havia sido realizado por sua Cia ou P1, neste momento foi atualizado com sucesso! Você já pode logar no sistema!';
                                //atualizar para status ATIVO
                                $data['id_militar_estadual'] = (int) $id_militar_estadual;
                                $data['status'] = 'ativo';
                                $data['email'] = $post['email'];
                                $data['senha'] = md5($post['senha']);
                                $read->executaAtualizacao($id_militar_estadual, $data);
                            else:
                                $query = "INSERT INTO militar_estadual SET posto = :posto, nome = :nome, sobrenome = :sobrenome, rg = :rg, nf = :nf, unidade = :unidade, subunidade = :subunidade, email = :email, senha = :senha";
                                $sql = $pdo->prepare($query);
                                $sql->bindValue("posto", $posto);
                                $sql->bindValue("nome", $nome);
                                $sql->bindValue("sobrenome", $sobrenome);
                                $sql->bindValue("rg", $rg);
                                $sql->bindValue("nf", $nf);
                                $sql->bindValue("unidade", $unidade);
                                $sql->bindValue("subunidade", $subunidade);
                                $sql->bindValue("email", $email);
                                $sql->bindValue("senha", $senha);

                                $sql->execute();

                                if ($sql->rowCount() > 0):
                                    echo 'Seus dados foram armazenado com sucesso. Após a verificação de dados, seu cadastro será efetivado!';
                                    $Contato['Assunto'] = 'Inclusão de cadastro de novo usuário do domínio spvpmes@spvpmes.tk!';
                                    $Contato['DestinoNome'] = 'Sistema de Avaliação Profissional';
                                    $Contato['DestinoEmail'] = 'spvpmes@spvpmes.tk';
                                    $Contato['Mensagem'] = 'Cadastro de usuário (Militar Estadual) na tela de login do Sistema de Valorização Profissional.';
                                    $Contato['RemetenteNome'] = $nome . ' ' . $sobrenome;
                                    $Contato['RemetenteEmail'] = $email;

                                    $SendMail = new Email;
                                    $SendMail->Enviar($Contato);

                                    if ($SendMail->getError()):
                                        WSErro($SendMail->getError()[0], $SendMail->getError()[1]);
                                    endif;

                                    exit();
                                endif;
                            endif;
                        endif;
                    } catch (Exception $ex) {
                        if (isset($ex->errorInfo[1]) && $ex->errorInfo[1] == '1062'):
                            explode(' ', $ex->errorInfo[2]);
                            $msg = str_replace('Duplicate entry', 'Já existe ', $ex->errorInfo[2]);
                            $msg = str_replace('for key', 'cadastrado no sistema. Informe outro ', $msg);
                            echo 'ERRO: Cadastro não realizado!   ' . $msg;
                        endif;
                    }
                    ?>

                    <form method="post" class="panel panel-title" style="padding: 2% 5%; background-color: rgba(0,155,0,0.2); border-radius: 5%;">
                        <h3>Formulário de Cadastro</h3>
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>Posto/Graduação</label>
                                <select class="form-control" name="posto" required>
                                    <option disabled="" selected=""></option>
                                    <option>SD PM</option>
                                    <option>CB PM</option>
                                    <option>3º SGT PM</option>
                                    <option>2º SGT PM</option>
                                    <option>1º SGT PM</option>
                                    <option>ST PM</option>
                                    <option>ASP PM</option>
                                    <option>2º TEN PM</option>
                                    <option>1º TEN PM</option>
                                    <option>CAP PM</option>
                                    <option>MAJ PM</option>
                                    <option>TEN CEL PM</option>
                                    <option>CEL PM</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Nome</label>
                                <input class="form-control" type="text" name="nome" required/>
                            </div>
                            <div class="form-group col-md-7">
                                <label>Sobrenome</label>
                                <input class="form-control" type="text" name="sobrenome" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>RG</label>
                                <input class="form-control" type="number" name="rg" placeholder="apenas números" min="12000" max="300000" required/>
                            </div>
                            <div class="form-group col-md-3">
                                <label>NF</label>
                                <input class="form-control" type="number" name="nf" placeholder="apenas números" min="" max="" required/>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Unidade</label>
                                <select class="form-control" name="unidade">
                                    <option disabled="" selected=""></option>
                                    <option disabled="">CPOs</option>
                                    <option>Metropolitano</option>
                                    <option>Norte</option>
                                    <option>Sul</option>
                                    <option>Noroeste</option>
                                    <option>Serrano</option>
                                    <option>Especializado</option>
                                    <option disabled=""></option>
                                    <option disabled="">BATALHÕES</option>
                                    <option>1º BPM</option>
                                    <option>2º BPM</option>
                                    <option>3º BPM</option>
                                    <option>4º BPM</option>
                                    <option>5º BPM</option>
                                    <option>6º BPM</option>
                                    <option>7º BPM</option>
                                    <option>8º BPM</option>
                                    <option>9º BPM</option>
                                    <option>10º BPM</option>
                                    <option>11º BPM</option>
                                    <option>12º BPM</option>
                                    <option>13º BPM</option>
                                    <option>14º BPM</option>
                                    <option disabled=""></option>
                                    <option disabled="">OMEs Especializadas</option>
                                    <option>CIMEsp</option>
                                    <option>BPTran</option>
                                    <option>BPMA</option>
                                    <option>RPMont</option>
                                    <option>CiaPGuarda</option>
                                    <option disabled=""></option>
                                    <option disabled="">CIAS IND.</option>
                                    <option>2ª CiaInd</option>
                                    <option>6ª CiaInd</option>
                                    <option>8ª CiaInd</option>
                                    <option>9ª CiaInd</option>
                                    <option>10ª CiaInd</option>
                                    <option>11ª CiaInd</option>
                                    <option>12ª CiaInd</option>
                                    <option>13ª CiaInd</option>
                                    <option>14ª CiaInd</option>
                                </select>           
                            </div>
                            <div class="form-group col-md-3">
                                <label>Subunidade/Seção</label>
                                <select class="form-control" name="subunidade">
                                    <option disabled="" selected=""></option>
                                    <option disabled="">SEÇÕES</option>
                                    <option>Cmd</option>
                                    <option>Subcmd</option>
                                    <option>P1</option>
                                    <option>P2</option>
                                    <option>P3</option>
                                    <option>P4</option>
                                    <option>SPAJM</option>
                                    <option>Outra</option>
                                    <option disabled="" selected=""></option>
                                    <option disabled="">COMPANHIAS</option>
                                    <option>1ª Cia</option>
                                    <option>2ª Cia</option>
                                    <option>3ª Cia</option>
                                    <option disabled="" selected=""></option>
                                    <option disabled="">PELOTÕES</option>
                                    <option>1º Pel</option>
                                    <option>2º Pel</option>
                                    <option>3º Pel</option>

                                </select>    
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>E-mail:</label>
                                <input class="form-control" type="email" name="email" required/>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Senha:</label>
                                <input class="form-control" type="password" name="senha" required/>
                            </div>
                        </div>

                        <input class="btn btn-primary"type="submit" value="Cadastrar" name="cadastrar" />

                    </form>
                </div>
            </div>
    </body>
</html>
