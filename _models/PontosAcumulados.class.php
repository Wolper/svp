
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MilitarEstadual
 *
 * @author dikson
 */
class PontosAcumulados {

    private $data;
    private $militarEstadual;
    private $pontos_criterio;
    private $id_criterio;
    private $num_BU;
    private $totalPontos;
    private $idpontos;
    private $mesVigencia;
    private $result;
    private $error;

    const TABELA = 'pontos_acumulados';

    public function executaCadastro(array $data) {
        $this->data = $data;

//        $this->localizaIdMilitar();
//        $this->data['id_militar_estadual'] = $this->militarEstadual;

        $this->id_criterio = $data['id_criterio'];
        $this->localizaPontosCriterio();
        $this->data['total_de_pontos'] = $this->pontos_criterio;

        $this->estipulaMesVigencia();
        $this->data['mes_vigencia'] = $this->mesVigencia;


//        unset($this->data['nome_militar']);
        unset($this->data['id_criterio']);
        unset($this->data['data_BU']);

        $this->resgataPontos();
     
        if ($this->haPontos):
            $this->somaPontos();
        else:
            $this->cadastraPrimeirosPontos();
        endif;
    }

    private function cadastraPrimeirosPontos() {
        $cadastra = new Create();
        $cadastra->ExeCreate(self::TABELA, $this->data);

        if ($cadastra->getResult()):
            $this->error = ["O total de {$this->pontos_criterio} pontos foram adicionados com sucesso no sistema!", WS_ACCEPT];
            $this->result = $cadastra->getResult();
        else:
            $this->error = ["Erro: Não foi possível somar os pontos no sistema! <b>Entre em contato com o administrador.</b>", WS_ERROR];
        endif;
    }

    private function somaPontos() {
        $this->data['total_de_pontos'] = $this->totalPontos + $this->pontos_criterio;
       
        $atualiza = new Update();
        
        $atualiza->ExeUpdate(self::TABELA, $this->data, "WHERE id_pontos_acumulados = :id_pontos", "id_pontos={$this->idpontos}");

        if ($atualiza->getResult()):
            $this->error = ["O total de {$this->pontos_criterio} pontos foram atualizados com sucesso no sistema!", WS_ACCEPT];
            $this->result = true;
            $this->totalPontos = 0;
        endif;
    }

    public function getResult() {
        return $this->result;
    }

    public function getError() {
        return $this->error;
    }

    private function localizaIdMilitar() {
        $me = new Read();
        $me->ExeRead("militar_estadual", "WHERE nome = :nome", "nome={$this->data['nome_militar']}");

        if ($me->getRowCount() > 0):
            $this->militarEstadual = $me->getResult()[0]['id_militar_estadual'];
        endif;
    }

    private function localizaPontosCriterio() {
        $crit = new Read();
        $crit->ExeRead("criterio", "WHERE id_criterio = :id_criterio", "id_criterio={$this->data['id_criterio']}");

        if ($crit->getRowCount() > 0):
            $this->pontos_criterio = $crit->getResult()[0]['pontos'];
        endif;
    }

    private function estipulaMesVigencia() {
        $this->mesVigencia = (string) $this->data['data_BU'];
        $this->mesVigencia = explode("-", $this->mesVigencia);
        $this->mesVigencia = (int) $this->mesVigencia[1];
    }

    private function resgataPontos() {
        $busca = new Read();
        $busca->ExeRead("pontos_acumulados", "WHERE id_militar_estadual = :id_militar AND mes_vigencia =:mes_vig", "id_militar={$this->militarEstadual}&mes_vig={$this->mesVigencia}");

        if ($busca->getRowCount() > 0):
            $this->totalPontos = $busca->getResult()[0]['total_de_pontos'];
            $this->idpontos = $busca->getResult()[0]['id_pontos_acumulados'];
            $this->haPontos = true;
        else:
            $this->haPontos = false;
        endif;
    }

}
