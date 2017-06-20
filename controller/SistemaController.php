<?php

/**
 * Controller do Sistema
 */
class SistemaController extends Controller {

    var $name = 'sistema';

    public function index() {
        $this->set("sistemaList", json_encode(Sistema::all()));
		$this->set("maquinaList", $this->maquinaTable());
    }

    public function salvar($dados) {
        $sistema = new Sistema($dados);
        $sistema = $sistema->persist();
        if ($sistema)
            return $sistema->getId();
    }

    public function carregar($dados) {
        return json_encode(Sistema::load($dados['id']));
    }

    public function excluir($dados) {
        $sistema = new Sistema($dados);
        return json_encode($sistema->delete());
    }

    public function sistemaTable() {
        return json_encode(Sistema::all());
    }
	
	public function maquinaTable() {
		$this->uses("Maquina");
		$maquinas = Maquina::select(array('nome', 'ip', 'ativa'), array());
		$maquinaRows = "";
		foreach ($maquinas as $maquina) 
			$maquinaRows .= "<tr><td>" . 
			$maquina['nome'] . "</td><td>" . 
			$maquina['ip'] . "</td><td>" . 
			($maquina['ativa'] ? 'Sim' : 'Não') . 
			"</td><td style=\"text-align: center;\"><button class=\"selecionar-maquina-button reduz-button\">Selecionar</button></td></tr>";
		return $maquinaRows;
	}

    public function table() {
        $this->set("sistemaList", Sistema::allMap());
    }

}
