var Maquina;

Maquina = (function() {

    function Maquina(data) {
        if (typeof data != "object")
            return;
        this.id = data["id"];
        this.nome = data["nome"];
        this.ip = data["ip"];
        this.usuarioAcesso = data["usuarioAcesso"];
        this.senhaAcesso = data["senhaAcesso"];
        this.dataRegistro = new Date(data["dataRegistro"]);
        this.semDNS = data["semDNS"] ? (data["semDNS"] == "false" || data["semDNS"] == "0" ? false : true) : false;
        this.ativa = data["ativa"] ? (data["ativa"] == "false" || data["ativa"] == "0" ? false : true) : false;
        
    }

    Maquina.prototype.formatar = View.prototype.formatar;
    
    Maquina.prototype.htmlTable = View.prototype.htmlTable;
	
	Maquina.prototype.carregarForm = View.prototype.carregarForm;
    
    return Maquina;
})();