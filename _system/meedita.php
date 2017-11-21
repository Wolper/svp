<!DOCTYPE html>
<?php
require 'index.php';

$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (isset($post['id_militar_estadual'])):
    $readME = new MilitarEstadual();
    $data = $readME->ler($post['id_militar_estadual']);

endif;
?>

<div class="tab-content">

    <!--ABA DE EDIÇÃO DE MILITAR-->
    <div id="editarMilitar" class="tab-pane active">
        <h3>Edição do Registro de Militares Estaduais</h3>
        <p class="alert-info text-center">Para edição de registro de militares estaduais é necessário preencher todos os campos.</p>

        <form method="post" action="" name="selecao">
            <div class="form-group">
                <label>Selecione o ME</label>
                <select class="form-control" name="id_militar_estadual">
                    <option></option>';
                    <?php
                    $me = new Read();
                    $me->ExeRead("militar_estadual");
                    if ($me->getRowCount() > 0):
                        foreach ($me->getResult() as $mil):
                            echo "<option value=\"{$mil['id_militar_estadual']}\" ";
                            echo "> {$mil['posto']} {$mil['nome']} {$mil['sobrenome']} - Lotação: {$mil['subunidade']}/{$mil['subunidade']}</option>";
                        endforeach;
                    endif;
                    ?>       
                </select> 
            </div>
            <input class="btn btn-primary" type="submit" name="escolhe" value="Escolher"/>
        </form><br/><br/><br/><br/>


        <form method="post" action="" name="formEdicaoMilitar">
            <div class="form-group">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nome:</label>
                        <input class="form-control" type="text" name="nome" value="<?= utf8_decode(isset($data['nome']) ? $data['nome'] : ''); ?>" required/>
                    </div>
                    <div class="form-group col-md-6" >
                        <label>Sobrenome:</label>
                        <input class="form-control" type="text" name="sobrenome"  value="<?= utf8_decode(isset($data['sobrenome']) ? $data['sobrenome'] : ''); ?>" required/>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-2" >
                        <label>Posto/Graduação</label>
                        <select class="form-control" name="posto"  required>
                            <option disabled="" selected=""><?= utf8_decode(isset($data['posto']) ? $data['posto'] : ''); ?></option>
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
                            <option disabled="" selected=""><?= utf8_decode(isset($data['unidade']) ? $data['unidade'] : ''); ?></option>
                            <option>3º BPM</option>
                            <option>9º BPM</option>
                            <option>9ª Cia Ind</option>
                            <option>10ª Cia Ind</option>
                            <option>CPOS</option>
                            <option>COPOM</option>
                            <option>CIODES</option>
                        </select>
                    </div>
                    <div class="col-md-2" >
                        <label>Lotação (Subunidade)</label>
                        <select class="form-control" name="subunidade" required>
                            <option disabled="" selected=""><?= utf8_decode(isset($data['subunidade']) ? $data['subunidade'] : ''); ?></option>
                            <option>Sede</option>
                            <option>1ªCia</option>
                            <option>2ªCia</option>
                            <option>3ªCia</option>
                            <option>2ªSeção</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Registro Geral (RG)</label>
                        <input class="form-control" type="number" name="rg"  value="<?= (isset($data['rg']) ? $data['rg'] : ''); ?>" required/>
                    </div>
                    <div class="col-md-3">
                        <label>Número Funcional (NF)</label>
                        <input class="form-control" type="number" name="nf"  value="<?= (isset($data['nf']) ? $data['nf'] : ''); ?>" required/>
                    </div>
                </div>
            </div>
            <input class="btn btn-success" type="submit" name="enviar" value="Editar ME" />
        </form>
    </div>
    <!--FIM DA ABA DE EDIÇÃO DE MILITAR-->

</div>
<?php
require '../rodape.php';
?>
</div>
</body>
</html>