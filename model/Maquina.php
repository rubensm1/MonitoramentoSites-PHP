<?php

/**
 * Classe Maquina
 */
class Maquina extends Model {

    protected static $useTable = "maquina";
    
    private $nome;
    private $ip;
    private $usuarioAcesso;
    private $senhaAcesso;
    private $dataRegistro;
    private $semDNS;
    private $ativa;
    
    function Maquina($data = NULL) {
        if ($data != NULL) {
            parent::__construct(isset($data['id']) ? (int) $data['id'] : NULL);
            $this->nome = isset($data["nome"]) && $data["nome"] != "" ? $data["nome"] : NULL;
            $this->ip = isset($data["ip"]) && $data["ip"] != "" ? $data["ip"] : NULL;
            $this->usuarioAcesso = isset($data["usuarioAcesso"]) && $data["usuarioAcesso"] != "" ? $data["usuarioAcesso"] : NULL;
            $this->senhaAcesso = isset($data["senhaAcesso"]) && $data["senhaAcesso"] != "" ? $data["senhaAcesso"] : NULL;
            $this->dataRegistro = isset($data["dataRegistro"]) && $data["dataRegistro"] != "" ? $data["dataRegistro"] : NULL;
            $this->semDNS = isset($data["semDNS"]) ? ( strtolower($data["semDNS"]) == "false" || $data["semDNS"] == "0" ? FALSE : (boolean)$data["semDNS"]) : NULL;
            $this->ativa = isset($data["ativa"]) ? ( strtolower($data["ativa"]) == "false" || $data["ativa"] == "0" ? FALSE : (boolean)$data["ativa"]) : NULL;
            
        } else
            parent::__construct();
    }

    public function toArray() {
        return get_object_vars($this);
    }

}