<!DOCTYPE html>
<?php
require 'index.php';
?>

<div class="tab-content">

    <!--ABA DE CADASTRO DE MILITAR-->
    <div id="cadastrarMilitar" class="tab-pane active">
        <h3>Cadastro de Militares Estaduais</h3>
        <p class="alert-info text-center">Para cadastro de militares estaduais é necessário preencher todos os campos.</p>
        <?php
        $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($post['enviar']) && !empty($post['enviar'])):
            $data['posto'] = addslashes($post['posto']);
            $data['nome'] = addslashes($post['nome']);
            $data['sobrenome'] = addslashes($post['sobrenome']);
            $data['unidade'] = addslashes($post['unidade']);
            $data['subunidade'] = addslashes($post['subunidade']);
            $data['rg'] = addslashes($post['rg']);
            $data['nf'] = addslashes($post['nf']);
            $data['email'] = '';
            $data['senha'] = '';
            $data['codigo'] = md5(rand(0, 9999) . rand(0, 9999));
            $data['status'] = 'inativo';

            $read = new MilitarEstadual();
            if ($read->buscaME($data['rg']) > 0):
                extract($read->buscaME($data['rg']));
                echo 'Cadastro não realizado! Já existe um ME cadastrado com este RG.';
                //atualizar para status ATIVO
                $data['id_militar_estadual'] = (int) $id_militar_estadual;
                $data['status'] = 'ativo';
                $data['email'] = $email;
                $data['senha'] = md5($senha);
                $read->executaAtualizacao($id_militar_estadual, $data);
            else:
                $create = new MilitarEstadual();
                $create->executaCadastro($data);
                if ($create->getResult()):
                    WSErro("O militar estadual foi cadastrado com sucesso no sistema!", WS_ACCEPT);
                else:
                    WSErro("Não foi possível cadastrar o militar estadual no sistema! Verifique se não há duplicidade de RG ou NF", WS_ACCEPT);
                endif;
            endif;
        endif;
        ?>                   
        <form method="post" action="" name="formCadastroMilitar">
            <div class="form-group" >
                <div class="row">
                    <div class="form-group col-md-6" >
                        <label>Nome:</label>
                        <input class="form-control" type="text" name="nome" required/>
                    </div>
                    <div class="form-group col-md-6" >
                        <label>Sobrenome:</label>
                        <input class="form-control" type="text" name="sobrenome" required/>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-2" >
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
                    <div class="col-md-2" >
                        <label>Lotação (Unidade)</label>
                        <select class="form-control" name="unidade" required>
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
                    <div class="col-md-2" >
                        <label>Lotação (Subunidade)</label>
                        <select class="form-control" name="subunidade" required>
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
                    <div class="col-md-3">
                        <label>Registro Geral (RG)</label>
                        <input class="form-control" type="number" name="rg" placeholder="somente números"required/>
                    </div>
                    <div class="col-md-3">
                        <label>Número Funcional (NF)</label>
                        <input class="form-control" type="number" name="nf" placeholder="somente números" required/>
                    </div>
                </div>
            </div>
            <input class="btn btn-primary" type="submit" name="enviar" value="Cadastrar ME" />
        </form>
    </div>
    <!--FIM DA ABA DE CADASTRO DE MILITAR-->

</div>
<?php
require '../rodape.php';
?>
</div>
</div>
</body>
</html>