<script src="<?php echo CONTEXT_PATH; ?>/view/sistema/clientWebSocket.js"></script>

<div id="panelSSH" class="panel panel-free"  style="width: 40%; float: right;">
    <div class="panel-heading">SSH</div>
	<div>
		<pre style="color: white; background-color: black; height: 400px;"></pre>
	</div>
	<div class="panel-footer" style="text-align: center;">
		<input type="text" value="" style="width: 100%; margin-bottom: 10px;" /> 
		<button id="conectarHomologacao" style="width: 250px;">Conectar Homologação</button>
		<button id="conectarProducao" style="width: 250px;">Conectar Produção</button>
		<br />
		<output id="mensagens" />
	</div>
</div>

<script>
var PanelSSH;
PanelSSH = (function (){
	function PanelSSH(sistema) {
		this.panel = $("#panelSSH");
		this.conteudo = $("#panelSSH pre");
		this.inputComando = $("#panelSSH input");
		this.mensagens = $("#panelSSH output");
		this.btConectarHomologacao = $($("#panelSSH button").get(0));
		this.btConectarProducao = $($("#panelSSH button").get(1));
		this.sistema = sistema;
		this.conec = null;
		this.ativarListeners();
	}
	PanelSSH.prototype.ativarListeners = function() {
		var thisLocal = this;
			
		this.btConectarHomologacao.click(function () {
			thisLocal.conectarServidor (sistema.maquinaHomologacao);
		});
		this.btConectarProducao.click(function(){
			thisLocal.conectarServidor (sistema.maquinaProducao);
		});
		this.inputComando.keyup (function (event) {
			if (thisLocal.conec && event.keyCode == 13 && this.value.trim() != "") {
				thisLocal.conec.comando(this.value); 
				this.value='';
			}
		});
	};
	
	PanelSSH.prototype.conectarServidor = function(maquina) {
		this.conec = new ClientWebSocketSSH(5555, this);
		this.conec.conectar();
		this.btConectarHomologacao.prop( "disabled", true );
		this.btConectarProducao.prop( "disabled", true );
	}
	PanelSSH.prototype.exibeTextoTerminal = function(texto) {
		this.conteudo.append( "\n" + texto);
	}
	/**
	 * Exibe uma mensagem
	 * @param {String}  msg    o texto da mensagem
	 * @param {boolean} isErro destaca a mensagem em vermelho, indicando que é mensagem de erro, se informado 'true'
	 * @param {boolean} fix    se true, a mensagem permanece visível; se false, depois de 3 segundos desaparece
	 * @return {void}
	 */
	PanelSSH.prototype.mensagem = function(msg, isErro, fix) {
		if (isErro)
			msg = "<span style='color: red;'>" + msg + "</span>";
		this.mensagens.html( msg );
		if (typeof timeout == "number")
			window.clearTimeout(timeout);
		if (!fix)
			timeout = window.setTimeout(function () {
				this.mensagens.html( "" );
			}, 3000);
	}
	return PanelSSH;
})();


</script>