<!DOCTYPE html>
<?php
require './index.php';
$qteLinhas = 3;
?>
<div class="tab-content">

    <!--ABA DE VISUALIZAÇÃO GERAL-->
    <div id="pontuacao" class="tab-pane active">
        <h3>Pontuação Geral</h3>

        <div width="100%">
            <table id="tabela" border="0" width="100%" style="text-align: left">

                <?php
                $mes = date('m');

                $sql = "SELECT *, SUM(pt.total_de_pontos) AS total FROM (militar_estadual AS me JOIN pontos_acumulados AS pt ON me.id_militar_estadual = pt.id_militar_estadual) WHERE pt.mes_vigencia = {$mes}  AND pt.ativo = 1 GROUP BY pt.mes_vigencia, me.subunidade, me.unidade ORDER BY pt.total_de_pontos DESC";

                $sql = $pdo->query($sql);
                if ($sql->rowCount() > 0):
                    ?>
                    <tr>
                        <th>Posto/Graduação</th>
                        <th>Nome</th>
                        <th>Lotação</th>
                        <th>Pontuação</th>
                        <th>Mês de Vigência</th>
                    </tr>
                    <tr id="linha">
                    <tbody>
                        <?php
                        foreach ($sql->fetchAll() as $usuario):
                            extract($usuario);
                            echo '<td>' . $posto . '</td>';
                            echo '<td>' . $nome . ' ' . $sobrenome . '</td>';
                            echo '<td>' . $subunidade . ' / ' . $unidade . '</td>';
                            echo '<td id="totalpt">' . $total . '</td>';
                            echo '<td id="mesvig">' . $mes_vigencia . '</td>';
                            echo '<tr>';
                        endforeach;

                    else:
                        echo '<br/><br/><br/><br/>';
                        echo '<b>Não há militares estaduais com pontos cadastrados!</b>';

                    endif;
                    ?>
                </tbody>
            </table>
            <ul class="pager">
                <li class="previous"><a href="">Anterior</a></li>
                <li class="next"><a href="">Próxima</a></li>
            </ul>
        </div>
    </div>
    <!--FIM ABA DE VISUALIZAÇÃO GERAL-->

</div>
<?php
require '../rodape.php';
?>
</div>
</body>
</html>
