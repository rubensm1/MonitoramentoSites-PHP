<?php

/**
 * Controller do Maquina
 */
class MaquinaController extends Controller {

    var $name = 'maquina';

    public function index() {
        $this->set("maquinaList", json_encode(Maquina::all()));
    }

    public function salvar($dados) {
        $maquina = new Maquina($dados);
        $maquina = $maquina->persist();
        if ($maquina)
            return $maquina->getId();
    }

    public function carregar($dados) {
        return json_encode(Maquina::load($dados['id']));
    }

    public function excluir($dados) {
        $maquina = new Maquina($dados);
        return json_encode($maquina->delete());
    }

    public function maquinaTable() {
        return json_encode(Maquina::all());
    }

    public function table() {
        $this->set("maquinaList", Maquina::allMap());
    }

}
