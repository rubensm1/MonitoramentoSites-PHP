<script src="view/monitoramento/statusConexao.js"></script>
<script src="view/monitoramento/mensagem.js"></script>
<script src="view/monitoramento/servico.js"></script>
<script src="view/monitoramento/tabelaStatusConexao.js"></script>
<script>

	var tabelaStatusConexao = null;
	var cws = null;
	
	function geraLink() {
		return "ws://"+document.location.hostname;
	}
	
	/**
	 * Exibe uma mensagem
	 * @param {String}  msg    o texto da mensagem
	 * @param {boolean} isErro destaca a mensagem em vermelho, indicando que é mensagem de erro, se informado 'true'
	 * @param {boolean} fix    se true, a mensagem permanece visível; se false, depois de 3 segundos desaparece
	 * @return {void}
	 */
	function addMensagem(msg, isErro, fix)
	{
		var elMens = document.getElementById("mensagens");
		if (isErro)
			msg = "<span style='color: red;'>" + msg + "</span>";
		elMens.innerHTML = msg;
		if (typeof timeout == "number")
			window.clearTimeout(timeout);
		if (!fix)
			timeout = window.setTimeout(function () {
				elMens.innerHTML = "...";
			}, 3000);
	}

	var ClientWebSocket = (function (){
		
		function ClientWebSocket(porta) {
			this.porta = porta;
		}
		
		ClientWebSocket.prototype.conectar = function () {
			this.conexao = new WebSocket(this.porta? geraLink()+":"+this.porta : geraLink());
			//this.conexao = new WebSocket("ws://"+document.location.hostname+"/basic/ws");
			var thisLocal = this;
			this.conexao.onopen = function() {
				//conexao.send('Ping');
				addMensagem("Conectado!", false, true);
				tabelaStatusConexao.habilitarCheckBoxs();
			};
			this.conexao.onerror = function(error) {
				console.log('WebSocket Error ' + error);
			};
			this.conexao.onmessage = function(e) {
				var obj = new Mensagem(e.data);
				//console.log(obj);
				if (obj.erro != null && obj.erro != "")
				{
					groww.renderMessage(new MensagemGrowl(obj.erro));
					return;
				}
				switch (obj.type) {
					case "init":
						tabelaStatusConexao.iniciaStatusConexoes(JSON.parse(obj.dados));
						break;
					case "ping":
						thisLocal.mostraStatus(new StatusConexao(obj.dados));
						break;
					case "desat":
						thisLocal.mostraStatus(new StatusConexao(parseInt(obj.dados),"",""));
						break;
					default:

						break;
				}
			};
			this.conexao.onclose = function(evt) {
				console.log('WebSocket Close ' + evt);
				addMensagem("Desconectado!", false, true);
				kkk = evt;
				tabelaStatusConexao.desabilitarCheckBoxs();
				tabelaStatusConexao.limparConexoes();
			};
		}
		ClientWebSocket.prototype.desconectar = function () {
			this.conexao.close();
			this.conexao = null;
			addMensagem("Desconectado!", false, false);
		}
				
		
		
		ClientWebSocket.prototype.mostraStatus = function (statusConexao) {
			tabelaStatusConexao.atualizaStatus(statusConexao);
		}
		ClientWebSocket.prototype.setaSistema = function (sistema) {
			console.log(sistema);
			this.enviarMensagem(sistema);
			if (sistema.ativo) {
				this.mostraStatus(new StatusConexao(sistema.id, "...","Conectando..."));
			}
			else {
				this.mostraStatus(new StatusConexao(sistema.id, "",""));
			}
		}
		ClientWebSocket.prototype.enviarMensagem = function (sistema) {
			var msg = new Mensagem("conect", "SistemaAmbiente", JSON.stringify(sistema), "");
			console.log(msg);
			this.conexao.send(JSON.stringify(msg));
			//document.getElementById(link.id).value = '';
		}
		ClientWebSocket.prototype.enforcaServidor = function () {
			var msg = new Mensagem("matar", "String", "", "");
			this.conexao.send(JSON.stringify(msg));
		}
		
		return ClientWebSocket;
	})(); 
</script>

<div class="panel panel-jquery">
    <div id="mensagens" class="panel-heading">Monitoramento</div>
    <div id="div-sistema-table" class="div-model-table" class="panel-body">
        <?php echo $dados; ?>
    </div>
</div>

<script>
	addMensagem("Desconectado!", false, true);
	tabelaStatusConexao = new TabelaStatusConexao("local");
	cws = new ClientWebSocket(5555);
	cws.conectar();
</script>
