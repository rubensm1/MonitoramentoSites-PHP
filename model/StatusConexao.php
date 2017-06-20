<?php

/**
 * Classe StatusConexao
 */
class StatusConexao extends Model {

    protected static $useTable = FALSE;
    
    private $delay;
	private $codigo;
    private $descricao;
    
    function StatusConexao($data = NULL) {
        if ($data != NULL) {
            parent::__construct(isset($data['id']) ? (int) $data['id'] : NULL);
            $this->delay = isset($data["delay"]) && $data["delay"] != "" ? (int) $data["delay"] : NULL;
			$this->codigo = isset($data["codigo"]) && $data["codigo"] != "" ? $data["codigo"] : NULL;
            $this->descricao = isset($data["descricao"]) && $data["descricao"] != "" ? $data["descricao"] : NULL;
        } 
        else
            parent::__construct();
    }

    public function toArray() {
        return get_object_vars($this);
    }

}

