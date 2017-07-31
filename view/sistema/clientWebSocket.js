
var ClientWebSocketSSH;
ClientWebSocketSSH = (function (){
	function ClientWebSocketSSH(porta, panelSSH) {
		this.porta = porta;
		this.panelSSH = panelSSH;
	}
	
	ClientWebSocketSSH.prototype.conectar = function () {
		this.conexao = new WebSocket("ws://"+document.location.hostname+":"+this.porta);
		//this.conexao = new WebSocket("ws://"+document.location.hostname+"/basic/ws");
		var thisLocal = this;
		this.conexao.onopen = function(msg) {
			
		};
		this.conexao.onmessage = function(msg){
			console.log(msg.data);
			var obj = JSON.parse(msg.data);
			if (obj.error != null && obj.error != "")
			{
				//thisLocal.panel.abrir(obj.error, true);
				thisLocal.panelSSH.mensagem(obj.error, true, false);
				return;
			}
			switch(obj.type) {
				case "comando":
					thisLocal.panelSSH.exibeTextoTerminal (obj.dados);
					break;
				case "http":
					var doc = document.createElement("iframe");
					doc.innerHTML = obj.dados;
					document.body.appendChild (doc);
					break;
			}
		};
		this.conexao.onerror = function(msg) {
			console.error(msg)
		}; 
		this.conexao.onclose = function(msg){
			console.log("Conexão fechada\n" + msg);
		};
	}
	
	ClientWebSocketSSH.prototype.testarConexao = function()
	{
		if (this.conexao != null)
		{

			return;
		}
		this.panelSSH.mensagem("Testando...", false, false);
		setEstado("testando");
		if (isNaN(this.porta) || this.porta < 1024 || this.porta > 49151)
		{
			this.panelSSH.mensagem("Valor inválido para porta!", true);
			return;
		}
		this.conexao = new WebSocket("ws://" + document.location.hostname + ":" + this.porta);
		this.conexao.onopen = function (msg) {
			setEstado("desconectado");
			this.panelSSH.mensagem("", false, false);
			this.conexao.close();
		};
		this.conexao.onmessage = function (msg) {
			var obj = JSON.parse(msg.data);
			console.log(msg.data);
			if (obj.error != null && obj.error != "")
			{
				this.panelSSH.mensagem(obj.error, true, true);
				return;
			}
		};
		this.conexao.onerror = function (msg) {
			this.panelSSH.mensagem("Servidor indisponível!", true, false);
			setEstado("desativado");
		};
		this.conexao.onclose = function (msg) {
			console.log(msg);
			this.conexao = null;
		};
	}
	
	/**
	 * Desconecta o WebSocket do servidor;
	 * @return void
	 */
	ClientWebSocketSSH.prototype.desconectar = function () {
		this.conexao.close();
		this.conexao = null;
		setEstado("desconectado");
		this.panelSSH.mensagem("Desconectado!", false, false);
	}
	
	ClientWebSocketSSH.prototype.comando = function (mensagem) {
		var msg = new Object();
		msg.type = "comando";
		msg.classe = "String"
		msg.dados = mensagem; 
		this.conexao.send(JSON.stringify(msg));
	};
	
	return ClientWebSocketSSH;
})();