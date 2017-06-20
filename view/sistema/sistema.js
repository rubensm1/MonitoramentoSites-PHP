var Sistema;

Sistema = (function() {

    function Sistema(data) {
        if (typeof data != "object")
            return;
        this.id = data["id"];
        this.nome = data["nome"];
        this.urlProducao = data["urlProducao"];
        this.urlHomologacao = data["urlHomologacao"];
        this.maquinaProducao = data["maquinaProducao"] ? new Maquina (data["maquinaProducao"]) : null;
		this.maquinaHomologacao = data["maquinaHomologacao"] ? new Maquina (data["maquinaHomologacao"]) : null;
    }

    Sistema.prototype.formatar = function (dado,coluna) {
		var retorno;
		if (typeof dado == "boolean")
			retorno = dado ? "Sim" : "Não";
		else if (dado instanceof Date)
			retorno = dado.toLocaleDateString();
		else if (dado instanceof Maquina) 
			retorno = dado.nome + " (" + dado.ip + ")";
		else 
			retorno = dado;
		return "<td>"+retorno+"</td>";
	}
    
    Sistema.prototype.htmlTable = View.prototype.htmlTable;
	
	Sistema.prototype.carregarForm = View.prototype.carregarForm;
    
    return Sistema;
})();