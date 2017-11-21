<!DOCTYPE html>
<?php
require 'index.php';
?>

<div class="tab-content">

    <!--ABA DE CADASTRO DE PONTOS-->
    <div id="cadastrarPontos" class="tab-pane active">
        <h3>Cadastrar Pontos</h3>
        <p class="alert-info text-center">Para cadastro dos pontos é necessário preencher todos os campos.</p>
        <?php
        $post2 = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($post2['sendForm']) && !empty($post2['sendForm'])):
            $data2['historico'] = utf8_encode(addslashes($post2['historico']));
            $data2['num_BU'] = addslashes($post2['num_BU']);
            $data3['data_BU'] = $data2['data_BU'] = addslashes($post2['data_BU']);
            $data3['id_criterio'] = $data2['id_criterio'] = addslashes($post2['id_criterio']);
            $data3['id_militar_estadual'] = $data2['id_militar_estadual'] = utf8_encode(addslashes($post2['id_militar_estadual']));

            $cadasOcor = new Pontuacao();

            if (!$cadasOcor->cadastroDuplicado()):
                $cadasOcor->executaCadastro($data2);
                if ($cadasOcor->getResult()):
                    $cadasPontos = new PontosAcumulados();
                    $cadasPontos->executaCadastro($data3);
                else:
                    WSErro($cadasOcor->getError()[0], $cadasOcor->getError()[1]);
                endif;
            endif;
        endif;

        $sql = "SELECT * FROM militar_estadual";
        $sql = $pdo->query($sql);
        ?>

        <form method="post">
            <div class="form-group">
                <label>Selecione o ME</label>
                <select class="form-control" name="id_militar_estadual">
                    <option></option>';
                    <?php
                    if ($sql->rowCount() > 0):
                        foreach ($sql->fetchAll() as $data):
                            extract($data);
                            echo "<option value=\"{$id_militar_estadual}\" ";
                            echo "> {$posto} {$nome} {$sobrenome} - Lotação: {$unidade}/Lotação: {$subunidade} </option>";
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
            <div class="row">
                <div class="form-group col-md-2">
                    <label>Boletim Unificado (BU)</label>
                    <input class="form-control" type="number" placeholder="Nº do BU" name="num_BU" required />  
                </div>

                <div class="form-group col-md-2">
                    <label>Data do BU</label>
                    <input class="form-control" type="date" name="data_BU" required/>   
                </div>

                <div class="form-group col-md-8">
                    <label>Critério Operacional</label>
                    <select  class="form-control" name="id_criterio" title="Tipo de Ocorrência Registrada" required>
                        <option></option>
                        <option style="background-color: blue; color: white" disabled="disabled">GRUPO 01 - PRISÃO EM FLAGRANTE DELITO PARA</option>
                        <?php
                        $sql = "SELECT * FROM criterio";
                        $sql = $pdo->query($sql);

                        if ($sql->rowCount() > 0):
                            foreach ($sql->fetchAll() as $criterios):
                                extract($criterios);
                                echo "<option value=\"{$id_criterio}\" ";
                                echo ">{$descricao_criterio}</option>";
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Histórico</label>
                <textarea class="form-control" name="historico" placeholder="Descrição sucinta (de quem foi preso/apreendido ou o que foi apreendido)" required></textarea>                
            </div>
            <input class="btn btn-primary" type="submit" value="Cadastrar Pontos" name="sendForm"/>
        </form>
    </div>
    <!--ABA DE CADASTRO DE PONTOS-->

</div>
<?php
require '../rodape.php';
?>
</div>
</body>
</html>