<!DOCTYPE html>
<?php
require 'index.php';
$id_militar_estadual;
$mes_referencia;
?>

<div class="tab-content">

    <!--ABA DE ESCOLHA DO MILITAR ESTADUAL-->
    <div id="cadastrarPontos" class="tab-pane active">
        <h3>Lançar Folgas</h3>
        <p class="alert-info text-center">Para lançar folgas é necessário preencher todos os campos.</p>
        <form method="post" action="" name="selecao">
            <div class="form-group">
                <label>Selecione o ME</label>
                <select class="form-control" name="id_militar_estadual">
                    <option selected="selected" disabled="">Selecione o nome do ME para aplicar a folga</option>';
                    <?php
                    $mes = date('m');
                    $sql = "SELECT * FROM (militar_estadual AS me JOIN pontos_acumulados AS pt ON me.id_militar_estadual = pt.id_militar_estadual) WHERE pt.mes_vigencia <= {$mes} AND pt.ativo = 1 GROUP BY pt.mes_vigencia, me.id_militar_estadual ORDER BY me.nome ASC";
                    $sql = $pdo->query($sql);
                    if ($sql->rowCount() > 0):

                        foreach ($sql->fetchAll() as $usuario):
                            extract($usuario);

                            echo "<option value=\"{$id_militar_estadual}\" ";
                            echo "> {$posto} {$nome} {$sobrenome} - Mês de vigência: {$mes_vigencia}</option>";
                            echo '<tr>';
                        endforeach;
                    endif;
                    ?>       
                </select>
            </div>
            <input type="hidden" name="mes_vigencia" value="<?= (isset($mes_vigencia) ? $mes_vigencia : '') ?>"/>
            <input class="btn btn-primary" type="submit" name="escolhe" value="Escolher"/>
        </form>



        <?php
        $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($post['id_militar_estadual']) && !empty($post['id_militar_estadual'])):
            $id_militar_estadual = addslashes($post['id_militar_estadual']);
            $mes_referencia = addslashes($post['mes_vigencia']);

            $sql = "SELECT * FROM militar_estadual WHERE id_militar_estadual = {$id_militar_estadual}";
            $sql = $pdo->query($sql);
            if ($sql->rowCount() > 0):
                extract($sql->fetch());
            endif;
            ?>

            <form method="post">
                <div class="row">
                    <div class="form-group col-md-8">
                        <label>Nome do ME Beneficiado</label>
                        <select class="form-control" name="nome_militar">
                            <?php
                            if (isset($nome)):
                                echo "<option value=\"{$nome}\" ";
                                echo "> {$posto} {$nome} {$sobrenome} - Lotado(a): {$subunidade}/{$unidade} </option>";
                            endif;
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Data da Folga</label>
                        <input class="form-control" type="date" name="data_folga" required/>   
                    </div>
                </div>

                <div class="form-group">
                    <label>Observações</label>
                    <textarea class="form-control" name="observacoes" placeholder="Observações sobre o benefício (folga) adquirido pelo ME" ></textarea>                
                </div>
                <input type="hidden" name="id_militar_estadual" value="<?= (isset($id_militar_estadual) ? $id_militar_estadual : '') ?>"/>
                <input type="hidden" name="mes_vigencia" value="<?= (isset($mes_vigencia) ? $mes_vigencia : '') ?>"/>
                <input class="btn btn-primary" type="submit" value="Cadastrar Pontos" name="sendForm"/>
            </form>
        </div>

        <?php
    endif;

    if (isset($post['sendForm']) && !empty($post['sendForm'])):

        $dados['id_militar_estadual'] = addslashes($post['id_militar_estadual']);
        $dados['mes_referencia'] = addslashes($post['mes_vigencia']);
        $dados['observacoes'] = utf8_encode(addslashes($post['observacoes']));
        $dados['data_folga'] = addslashes($post['data_folga']);

        $lancaFolga = new Create();
        $lancaFolga->ExeCreate("folga", $dados);

        if ($lancaFolga->getResult() > 0):

            $desativar = "UPDATE pontos_acumulados SET ativo = 0 WHERE id_militar_estadual = {$dados['id_militar_estadual']} AND mes_vigencia = {$dados['mes_referencia']}";
            $desativar = $pdo->query($desativar);

            if ($desativar->rowCount() > 0):
                WSErro("A folga do militar estadual foi lançada com sucesso no sistema!", WS_ACCEPT);
                unset($post);
                unset($dados);
                echo '<meta HTTP-EQUIV=\'refresh\' CONTENT=\'5;URL=fgLanca.php\'>';
            endif;
        else:
            WSErro("Erro: A folga do militar estadual não foi lançada no sistema!", WS_ERROR);
        endif;

    endif;
    ?>


    <!--ABA DE CADASTRO DE PONTOS-->

</div>
<?php
require '../rodape.php';
?>
</div>
</body>
</html>