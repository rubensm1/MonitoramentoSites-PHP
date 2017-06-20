<?php

/**
 * Classe Sistema
 */
class SistemaAmbiente extends Sistema {

    protected static $useTable = FALSE;
    
    private $url;
	private $ativo;
    
    function SistemaAmbiente($data = NULL) {
        if ($data != NULL) {
            parent::__construct($data);
            $this->url = isset($data["url"]) && $data["url"] != "" ? $data["url"] : NULL;
			$this->ativo = isset($data["ativo"]) && $data["ativo"] != "" ? $data["ativo"] : NULL;
            
        } else
            parent::__construct();
    }

	public function getURL() {
		return $this->url;
	}
	
	public function isAtivo() {
		return $this->ativo;
	}
	
    public function toArray() {
        return get_object_vars($this);
    }

}