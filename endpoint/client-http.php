<html>
    <head>
		<style>
		    .ping-site {
				width: 120px;
			}
			div {
			    height: calc(100% - 80px);
				background-color: black;
				margin-bottom: 10px;
			}
			pre {
				color: white;
			}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script>
			/**
             * Exibe uma mensagem
             * @param {String}  msg    o texto da mensagem
             * @param {boolean} isErro destaca a mensagem em vermelho, indicando que é mensagem de erro, se informado 'true'
             * @param {boolean} fix    se true, a mensagem permanece visível; se false, depois de 3 segundos desaparece
             * @return {void}
             */
            function mensagem(msg, isErro, fix)
            {
                var elMens = document.getElementById("mensagens");
                if (isErro)
                    msg = "<span style='color: red;'>" + msg + "</span>";
                elMens.innerHTML = msg;
                if (typeof timeout == "number")
                    window.clearTimeout(timeout);
                if (!fix)
                    timeout = window.setTimeout(function () {
                        elMens.innerHTML = "";
                    }, 3000);
            }
			 /**
             * Altera o estado da interface
             * @param {String} estado ("desativado" | "conectado" | "desconectado" | "testando");
             * @return {void}
             */
            function setEstado(estado)
            {
                var statusLabel = document.getElementById("status");
                var portInput = document.getElementById("port");
                var dirInput = document.getElementById("dir");
                var invocarButton = document.getElementById("invocar");
                var conectarButton = document.getElementById("conectar");
                switch (estado)
                {
                    case "desativado":
                        statusLabel.innerHTML = "Desativado";
                        portInput.disabled = false;
                        dirInput.disabled = false;
                        invocarButton.innerHTML = "Iniciar Servidor";
                        conectarButton.innerHTML = "Conectar";
                        conectarButton.disabled = true;
                        invocarButton.disabled = false;
                        break;
                    case "conectado":
                        statusLabel.innerHTML = "Conectado!";
                        portInput.disabled = true;
                        dirInput.disabled = true;
                        invocarButton.innerHTML = "Parar Servidor";
                        conectarButton.innerHTML = "Desconectar";
                        conectarButton.disabled = false;
                        invocarButton.disabled = false;
                        break;
                    case "desconectado":
                        statusLabel.innerHTML = "Ativado / Admin Desconectado";
                        portInput.disabled = true;
                        dirInput.disabled = true;
                        invocarButton.innerHTML = "Parar Servidor";
                        conectarButton.innerHTML = "Conectar";
                        conectarButton.disabled = false;
                        invocarButton.disabled = true;
                        break;
                    case "testando":
                        //statusLabel.innerHTML = "Desativado";
                        portInput.disabled = true;
                        dirInput.disabled = true;
                        invocarButton.innerHTML = "Testando...";
                        conectarButton.innerHTML = "Conectar";
                        conectarButton.disabled = true;
                        invocarButton.disabled = true;
                        break;
                    default:
                        return;
                }
                estadoInterface = estado;
            }
			
			function ajaxPing () {
				var http = new XMLHttpRequest();
				http.addEventListener("progress", function (oEvent) {
					if (oEvent.lengthComputable) {
						var percentComplete = oEvent.loaded / oEvent.total;
						console.log(percentComplete + "%");
					} else {
						console.log("Não é possível calcular informações de progresso uma vez que a dimensão total é desconhecida");
					}
				}, false);
				http.addEventListener("load", function (evt) {
					arraybuffer = http.response;
					console.log("A transferência foi concluída.");
				}, false);
				http.addEventListener("error", function (evt) {
					console.log("Um erro ocorreu durante a transferência do arquivo.");
				}, false);
				http.addEventListener("abort", function (evt) {
					console.log("A transferência foi cancelada pelo usuário.");
				}, false);	
				
				//http.open("GET", "http://ufgdnet-hom.ufgd.edu.br/", true);
				//http.open("GET", "http://192.168.6.1/ssh/endpoint/client.php", true);
				http.open("GET", "http://127.0.0.1/ssh/endpoint/client.php", true);
				http.responseType = "arraybuffer";
				//http.setRequestHeader('Access-Control-Allow-Origin', '*');
				http.send();
				
			}
		
            var ClientWebSocket;
			ClientWebSocket = (function (){
				function ClientWebSocket(porta) {
					this.porta = porta;
				}
				
				ClientWebSocket.prototype.conectar = function () {
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
							mensagem(obj.error, true, false);
							return;
						}
						switch(obj.type) {
							case "comando":
								document.getElementById("conteudoMensagem").innerHTML += "\n" + obj.dados;
								//thisLocal.comando.conector(obj);
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
				
				ClientWebSocket.prototype.testarConexao = function()
				{
					if (this.conexao != null)
					{

						return;
					}
					mensagem("Testando...", false, false);
					setEstado("testando");
					if (isNaN(this.porta) || this.porta < 1024 || this.porta > 49151)
					{
						mensagem("Valor inválido para porta!", true);
						return;
					}
					this.conexao = new WebSocket("ws://" + document.location.hostname + ":" + this.porta);
					this.conexao.onopen = function (msg) {
						setEstado("desconectado");
						mensagem("", false, false);
						this.conexao.close();
					};
					this.conexao.onmessage = function (msg) {
						var obj = JSON.parse(msg.data);
						console.log(msg.data);
						if (obj.error != null && obj.error != "")
						{
							mensagem(obj.error, true, true);
							return;
						}
					};
					this.conexao.onerror = function (msg) {
						mensagem("Servidor indisponível!", true, false);
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
				ClientWebSocket.prototype.desconectar = function () {
					this.conexao.close();
					this.conexao = null;
					setEstado("desconectado");
					mensagem("Desconectado!", false, false);
				}
				
				ClientWebSocket.prototype.comando = function (mensagem) {
					var msg = new Object();
					msg.type = "comando";
					msg.classe = "String";
					msg.dados = mensagem; 
					this.conexao.send(JSON.stringify(msg));
				};
				
				return ClientWebSocket;
			})();
            
        </script>
    </head>
    <body>
		<div>
			<pre id="conteudoMensagem" style="color: white; background-color: black;"></pre>
		</div>
        <input id="inputComando" type="text" value="" /> <button id="iniciarClient">Start</button>
		<output id="mensagens" />
		<br />
		<br />
		<button class="ping-site" value="http://ufgdnet-hom.ufgd.edu.br/">UFGDNet-hom</button>
		<button class="ping-site" value="http://ajuda-hom.ufgd.edu.br/">Ajuda-hom</button>
		<button class="ping-site" value="http://biblioteca-hom.ufgd.edu.br/">Biblioteca-hom</button>
		<button class="ping-site" value="http://cartao-hom.ufgd.edu.br">Cartao-hom</button>
		<button class="ping-site" value="http://sigecad-coordenador-hom.ufgd.edu.br">Coordenador</button>
		<button class="ping-site" value="https://ufgdnet.ufgd.edu.br/">Ufgdnet</button>
        <script>
            var conec = null;
            document.getElementById("inputComando").onkeyup = function (event) {
                if (conec && event.keyCode == 13 && this.value.trim() != "") 
                {
                    conec.comando(this.value); 
                    this.value='';
                }
            };
			document.getElementById("iniciarClient").onclick = function () {
				conec = new ClientWebSocket(5555);
				conec.conectar();
				this.setAttribute("disabled","");
			}
			$(".ping-site").click(function (e) {
				conec.comando(this.value); 
			});
        </script>
    </body>
</html>


