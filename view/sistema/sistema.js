var Sistema;

Sistema = (function() {

    function Sistema(data) {
        if (data == null || typeof data != "object")
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
	
	Sistema.prototype.carregarForm = function (objeto, classe) {
		if (objeto == null) 
			objeto = this;
		if (classe == null)
			classe = this.constructor.name;
		classe = classe.toLowerCase();
		$("#sistema-form")[0].reset();
		//$("#sistema-form").deserializeObject(objeto);
		$("#sistema-form [name='id']").val(objeto.id ? objeto.id : "");
		$("#sistema-form [name='nome']").val(objeto.nome ? objeto.nome : "");
		$("#sistema-form [name='urlProducao']").val(objeto.urlProducao ? objeto.urlProducao : "");
		$("#sistema-form [name='urlHomologacao']").val(objeto.urlHomologacao ? objeto.urlHomologacao : "");
		$("#sistema-form [name='maquinaProducao']").val(objeto.maquinaProducao ? objeto.maquinaProducao.id : "");
		$("#sistema-form [name='maquinaHomologacao']").val(objeto.maquinaHomologacao ? objeto.maquinaHomologacao.id : "");
		selecionarMaquina(objeto.maquinaProducao, true);
		selecionarMaquina(objeto.maquinaHomologacao, false);
    };
    
    return Sistema;
})();