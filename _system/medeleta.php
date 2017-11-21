<!DOCTYPE html>
<?php
require 'index.php';
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (isset($post['id_militar_estadual']) && isset($post['excluir'])):
    $id = addslashes($post['id_militar_estadual']);
    $del = new MilitarEstadual();
    $data = $del->ler($id);
    $del->executaExclusao($id, $data);
    if ($del->getResult()):
        WSErro("O ME foi deletado do sistema!", WS_ACCEPT);
    else:
        WSErro("ERRO: Falha na exclusão do ME no sistema!", WS_ERROR);
    endif;
endif;
?>

<div class="tab-content">

    <!--ABA DE EXCLUSÃO DE MILITAR-->
    <div id="excluirMilitar" >
        <h3>Edição do Registro de Militares Estaduais</h3>
        <p class="alert-danger text-center">Tenha atenção na exclusão de registros! Após a exclusão não há como recuperar o registro.</p>
        <form class="form-group" method="post">
            <label>Selecione o ME</label>
            <select class="form-control" name="id_militar_estadual" />
            <option></option>
            <?php
            $me = new Read();
            $me->ExeRead("militar_estadual");
            if ($me->getRowCount() > 0):
                foreach ($me->getResult() as $mil):
                    echo "<option value=\"{$mil['id_militar_estadual']} \" ";
                    echo "> {$mil['posto']} {$mil['nome']} {$mil['sobrenome']} - Lotação: {$mil['subunidade']}/{$mil['unidade']} </option>";
                endforeach;
            endif;
            ?>       
            </select>
            <br/>
            <input type="submit" class="btn btn-danger" value="Excluir" name="excluir"/>
        </form>
    </div>
    <!--FIM DA ABA DE EXCLUSÃO DE MILITAR-->

</div>
<?php
require '../rodape.php';
?>
</div>
</body>
</html>