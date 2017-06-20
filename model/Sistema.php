<?php

include_once "Maquina.php";

/**
 * Classe Sistema
 */
class Sistema extends Model {

    protected static $useTable = "sistema";
    
    protected $nome;
    private $urlProducao;
    private $urlHomologacao;
	private $maquinaProducao;
	private $maquinaHomologacao;
    
    public function Sistema($data = NULL) {
        if ($data != NULL) {
            parent::__construct(isset($data['id']) ? (int) $data['id'] : NULL);
            $this->nome = isset($data["nome"]) && $data["nome"] != "" ? $data["nome"] : NULL;
            $this->urlProducao = isset($data["urlProducao"]) && $data["urlProducao"] != "" ? $data["urlProducao"] : NULL;
            $this->urlHomologacao = isset($data["urlHomologacao"]) && $data["urlHomologacao"] != "" ? $data["urlHomologacao"] : NULL;
			//$this->maquinaProducao = isset($data["maquinaProducao"]) && $data["maquinaProducao"] != "" ? (int) $data["maquinaProducao"] : NULL;
            //$this->maquinaHomologacao = isset($data["maquinaHomologacao"]) && $data["maquinaHomologacao"] != "" ? (int) $data["maquinaHomologacao"] : NULL;
			$this->maquinaProducao = isset($data["maquinaProducao"]) && $data["maquinaProducao"] != "" ? Maquina::load($data["maquinaProducao"]) : NULL;
            $this->maquinaHomologacao = isset($data["maquinaHomologacao"]) && $data["maquinaHomologacao"] != "" ? Maquina::load($data["maquinaHomologacao"]) : NULL;
        } else
            parent::__construct();
    }
	
	public function loadObjects() {
		
	}

    public function toArray() {
        return get_object_vars($this);
    }

}