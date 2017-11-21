<?php
require '../_app/config.php';
require '../_app/Config.inc.php';
require '../_models/MilitarEstadual.class.php';
require '../_models/Pontuacao.class.php';
require '../_models/PontosAcumulados.class.php';

session_start();
if (!isset($_SESSION['id']) || !$_SESSION['privilegio'] == 1 || !$_SESSION['status'] == 'ativo'):
    header("Location: ../login.php");
endif;
?>

<!DOCTYPE html>

<html>
    <head>
        <title>SVP</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../bootstrap/bootstrap.min.css" />
        <script type="text/javascript" src="../bootstrap/jquery.min.js"></script>
        <script type="text/javascript" src="../bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="../bootstrap/script.js"></script>
    </head>
    <body>
        <div class="container" style="text-align: center; padding-top: 2%; margin-top: 2%;  align-content: center; background-color: rgba(0,0,255,0.2)">
            <div style="margin-top: 2%;">
                <img width="100%" src="../css/cabeçalho.png">
                <!--            <div style="background-color: #ccc">
                                <h3>Sistema de Valorização Profissional<small></small></h3>
                                    <p>Sistema de Gestão de Pontos dos Policiais Militares</p>
                            </div>-->
            </div>
            <h1 class="jumbotron" style="font-family: monospace; margin-bottom: 0; padding: 0; color: #09f;font-size: 1.5em;" >Sistema de Valorização Profissional</h1>

            <!--MENU PRINCIPAL-->
            <nav class="navbar  navbar-inverse">
                <div class="container-fluid">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a class="dropdown-toggle " data-toggle="dropdown"
                               >Quadro Geral<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="view.php">Mês atual/Alfabética</a></li>
                                <li><a href="viewByCia.php">Mês atual/Lotação</a></li>
                                <li><a href="viewByPontos.php">Mês atual/Pontos</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown"
                               >Militares Estaduais<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="mecadastra.php">Cadastrar ME</a></li>
                                <li><a href="meedita.php">Editar ME</a></li>
                                <li><a href="medeleta.php">Excluir ME</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown"
                               >Pontuação<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="ptcadastra.php">Cadastrar Pontos</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown"
                               >Folgas<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="fgLanca.php">Lançar Folgas</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown"
                               >Relatórios<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a target="_blank" href="relatorioGeral.php">Pontuação Mensal</a></li>
                                <li><a target="_blank" href="relatorioBeneficiados.php">Beneficiados</a></li>
                                <li><a target="_blank" href="relatorioFolgas.php">Folgas Concedidas</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li data-toggle="modal" data-target="#infoPVP"><a>Informações Sobre o PVP</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="../logout.php">Logout</a></li>
                    </ul>
                    <div id="infoPVP" class="modal" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">Plano de Valorização Profissional</h4>
                                </div>
                                <div class="modal-body" style="text-align: justify">
                                    <p>Promover a valorização do público interno das Unidades,
                                        por meio do desenvolvimento e do reconhecimento profissional dos
                                        militares na execução de atividades operacionais ao procederem à
                                        apreensão de armas de fogo, drogas, bem como a prisão/apreensão de
                                        infratores em flagrante delito ou que estejam foragidos/aguardando
                                        cumprimento de mandado, recuperação de veículos/motocicletas roubadas/furtadas,
                                        além de outras situações previstas no presente plano. 
                                        O programa em comento não substitui outras formas de valorização já existentes
                                        nas Unidades, como o Destaque Operacional, concessão de elogios individuais
                                        em Boletim, elogios verbais em público e passará a vigorar a partir da data
                                        de sua publicação em BCGPM.</p>
                                </div>
                                <div class="modal-footer">
                                    <h4 class="modal-title text-center">Critérios de Pontuação</h4>
                                    <table class="table text-info">

                                        <tr><th style="background: #ccc">Grupo 01: Boletim de Prisão em Flagrante para</th></tr>
                                        <tr><th>Acusados(s) de homicídio - 10 pontos</th></tr>
                                        <tr><th>Latrocínio - 10 pontos</th></tr>
                                        <tr><th>Acusados(s) de homicídio tentado - 5 pontos</th></tr>
                                        <tr><th>Acusados(s) de violência contra a mulher - 3 pontos</th></tr>
                                        <tr><th>Acusados(s) de roubo - 5 pontos</th></tr>
                                        <tr><th>Acusados(s) de furto - 2 pontos</th></tr>
                                        <tr><th style="background: #ccc">Grupo 02: Boletim de Apreensão de</th></tr>
                                        <tr><th>Armas de fogo de uso restrito - 15 pontos</th></tr>
                                        <tr><th>Armas de fogo de uso permitido - 10 pontos</th></tr>
                                        <tr><th>Simulacros de Armas em situação de porte pessoal - 5 pontos</th></tr>
                                        <tr><th>Munição(s) até 10 unidades - 1 pontos</th></tr>
                                        <tr><th>Munição(s) acima de 10 unidades - 3 pontos</th></tr>
                                        <tr><th>Explosivo(s) independente de quantidade - 3 pontos</th></tr>
                                        <tr><th style="background: #ccc">Grupo 03: Boletim de Apreensão de Entorpecentes</th></tr>
                                        <tr><th>Até 30 unidades com pessoa detida - 2 pontos</th></tr>
                                        <tr><th>Acima de 30 a 100 unidades com pessoa detida - 10 pontos</th></tr>
                                        <tr><th>Até 30 unidades sem pessoa detida - 1 pontos</th></tr>
                                        <tr><th>Acima de 30 a 100 unidades sem pessoa detida - 5 pontos</th></tr>
                                        <tr><th>Acima de 100 unidades ou 1Kg com pessoa detida - 15 pontos</th></tr>
                                        <tr><th>Acima de 100 unidades ou 1Kg sem pessoa detida - 10 pontos</th></tr>
                                        <tr><th style="background: #ccc">Grupo 04: Boletim de Captura de Foragidos da Justiça</th></tr>
                                        <tr><th>Na lista de procurados da SESP ou 2ª Seção - 20 pontos</th></tr>
                                        <tr><th>Homicida - 10 pontos</th></tr>
                                        <tr><th>Roubo e Tráfico de Drogas - 3 pontos</th></tr>
                                        <tr><th>Demais crimes - 2 pontos</th></tr>
                                        <tr><th style="background: #ccc">Grupo 05: Fiscalização de Trânsito</th></tr>
                                        <tr><th>Boletim com teste positivo de alcoolemia de condutor - 5 pontos</th></tr>
                                        <tr><th>Apreensão de veículo roubado/furtado com conduzido - 5 pontos</th></tr>
                                        <tr><th>Apreensão de veículo roubado/furtado sem conduzido - 2 pontos</th></tr>
                                        <tr><th>Boletim de direção perigosa - 2 pontos</th></tr>
                                        <tr><th style="background: #ccc">Grupo 06: Ações Preventivas</th></tr>
                                        <tr><th>Realização de 30(RO) e 60(PCOM) visitas tranquilizadoras por mês - 10 pontos</th></tr>
                                        <tr><th>Aplicação do PROERD, até o 4º ano do EF - 10 pontos</th></tr>
                                        <tr><th>Aplicação do PROERD, do 5º ao 7º ano do EF - 10 pontos</th></tr>
                                        <tr><th style="background: #ccc">Grupo 07: Ações Gerais</th></tr>
                                        <tr><th>Apreensão de máquinas caça-níqueis ou produtos piratas - 1 pontos</th></tr>
                                        <tr><th>Disque-denúncia ou informação emitida c/ resultado (P2) - 2 pontos</th></tr>
                                        <tr><th>Ato relevante do operador do COPOM nessas ocorrência - 2 pontos</th></tr>
                                        <tr><th>Ato relevante não especificado acima a critério do Cmt da Unidade - 10 pontos</th></tr>
                                        <tr><th style="background: #d58512">Grupo 08: Perda de Pontos por</th></tr>
                                        <tr><th>Punição por transgressão leve ou advertência - (-5 pontos)</th></tr>
                                        <tr><th>Punição por transgressão média - (-10 pontos)</th></tr>
                                        <tr><th>Punição por transgressão grave - (-15 pontos)</th></tr>
                                        <tr><th>Punição por transgressão gravíssima - (-30 pontos)</th></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!--FIM DO MENU PRINCIPAL-->
