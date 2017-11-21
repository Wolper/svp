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
class MilitarEstadual {

    private $data;
    private $id_militar;
    private $result;
    private $error;

    const TABELA = 'militar_estadual';

    public function executaCadastro(array $data) {
        $data['nome'] = strtoupper($data['nome']);
        $data['sobrenome'] = strtoupper($data['sobrenome']);
        $this->data = $data;

        $this->cadastra();
    }

    public function getResult() {
        return $this->result;
    }

    public function getError() {
        return $this->error;
    }

    public function buscaME($rg) {
        $ler = new Read();
        $ler->ExeRead(self::TABELA, "WHERE rg = {$rg}");

        if ($ler->getRowCount() > 0):
            return $ler->getResult()[0];
        endif;
    }

    public function ler($id) {
        $ler = new Read();
        $ler->ExeRead(self::TABELA, "WHERE id_militar_estadual = {$id}");

        if ($ler->getRowCount() > 0):
            return $ler->getResult()[0];
        endif;
    }

    private function cadastra() {
        $cadastra = new Create();
        $cadastra->ExeCreate(self::TABELA, $this->data);

        if ($cadastra->getResult()):
            $this->error = ["O militar estadual {$this->data['nome']} {$this->data['sobrenome']} foi inserido no sistema!", WS_ACCEPT];
            $this->result = $cadastra->getResult();
        else:
            $this->error = ["Erro: O militar estadual {$this->data['nome']} {$this->data['sobrenome']} não foi inserido no sistema!", WS_ERROR];
        endif;
    }

    public function executaAtualizacao($idMilitar, array $data) {
        $this->id_militar = (int) $idMilitar;
        $this->data = $data;
       
        $this->atualiza();
    }

    private function atualiza() {

        $atualiza = new Update();
        $atualiza->ExeUpdate(self::TABELA, $this->data, "WHERE id_militar_estadual = :id", "id={$this->id_militar}");

        if ($atualiza->getResult()):
            $this->error = ["O dados do militar estadual foram atualizados com sucesso!", WS_ACCEPT];
            $this->result = true;
        endif;
    }

    public function executaExclusao($idMilitar, array $data) {
        $this->id_militar = (int) $idMilitar;
        $this->data = $data;

        $this->deleta();
    }

    private function deleta() {
        $deleta = new Delete();
        $deleta->ExeDelete(self::TABELA, "WHERE id_militar_estadual = :id", "id={$this->id_militar}");

        if ($deleta->getResult()):
            $this->error = ["O dados do militar estadual {$this->data['nome']} foram excluídos do sistema!", WS_ACCEPT];
            $this->result = true;
        endif;
    }

}
