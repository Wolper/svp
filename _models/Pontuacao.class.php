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
class Pontuacao {

    private $data;
    private $militarEstadual;
//    private $descricao_criterio;
    private $result;
    private $error;

    const TABELA = 'pontuacao';

    public function executaCadastro(array $data) {
        $this->data = $data;
 

//        $this->localizaIdMilitar();
//        $this->data['id_militar_estadual'] = $this->militarEstadual;

//        $this->localizaIdCriterio();
//        $this->data['id_criterio'] = $this->descricao_criterio;

//        unset($this->data['nome_militar']);
//        unset($this->data['descricao_criterio']);

        if (!empty($this->data['id_militar_estadual']) && !empty($this->data['id_criterio'])):
            $this->cadastraOcorrencia();
        endif;
    }

    public function cadastraOcorrencia() {
        $cadastra = new Create();
        $cadastra->ExeCreate(self::TABELA, $this->data);

        if ($cadastra->getResult()):
            $this->error = ["O registro de pontuação do Boletim Unificado de nº {$this->data['num_BU']} foi cadastrado no sistema!", WS_ACCEPT];
            $this->result = $cadastra->getResult();
        else:
            $this->error = ["Erro: Não foi possível inserir o registro de pontuação do Boletim Unificado de nº {$this->data['num_BU']}", WS_ERROR];
        endif;
    }

    public function cadastroDuplicado() {
        $busca = new Read();
        $busca->ExeRead("pontuacao", "WHERE id_militar_estadual = :id_militar AND num_BU = :num_BU", "id_militar={$this->militarEstadual}&num_BU={$this->data['num_BU']}");

        if ($busca->getRowCount() > 0):
            return true;
        else:
            return false;
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

//    private function localizaIdCriterio() {
//        $crit = new Read();
//        $crit->ExeRead("criterio", "WHERE descricao_criterio = :descricao_criterio", "descricao_criterio={$this->data['descricao_criterio']}");
//
//        if ($crit->getRowCount() > 0):
//            $this->descricao_criterio = $crit->getResult()[0]['id_criterio'];
//        endif;
//    }

}
