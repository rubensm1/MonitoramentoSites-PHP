<?php

require_once './WebSocket.php';
require_once './WSException.php';
require_once 'WSMensagem.php';
require_once "Net/SSH2.php"; 
require_once 'File/ANSI.php';


const USER = "rubensmarcon";
const SERVER = "homologacao2";
const PASS = "180113ramu";
$TERMINAL = USER . '@'. SERVER .':~$';

/*$ssh = new Net_SSH2('homologacao2');
if (!$ssh->login('coin', 'coinflyboys')) {
	exit('Login Failed');
}
echo $ssh->exec('pwd');
echo $ssh->exec('ls -la');

$ssh->disconnect();*/

class WebSocketImpl extends WebSocket {
	
	private $ssh;
	private $ansi;
	private $cl;
	
	private function mens ($m) {
		 $this->enviaDadoSocket( new WSMensagem("comando", "String", $m), $this->cl);
	}
	
	private function readTerminal() {
		global $TERMINAL;
		$ret = "";
		if ($this->ssh) {
			do {
				$ret = $this->ssh->read($TERMINAL);
				//var_dump( substr_compare($ret, $TERMINAL, strlen($ret) - strlen($TERMINAL)));
			} while ($ret !== FALSE && substr_compare($ret, $TERMINAL, strlen($ret) - strlen($TERMINAL)) !== 0);
		}
		return $ret;
	}

    protected function onMessage($obj, &$clientSocket) {
		global $TERMINAL;
		$this->cl = $clientSocket;
		
		$obj->dados = trim($obj->dados);
		
		switch ($obj->type) {
			case "conectar":
				$this->iniciaConexao();
				break;
			case "comando":
				$this->enviaComando($obj->dados);
				break;
			case "chat": 
			
				break;
		}
			
    }

    protected function onClose(&$clientSocket) {
        
    }

    protected function onOpen(&$clientSocket) {
		global $TERMINAL;
        /* enviar comando de aceite ao solicitante */
        $this->enviaDadoSocket(new WSMensagem('login', 'String', 'init'), $clientSocket);
		$this->cl = $clientSocket;
		$this->iniciaConexao();
    }

    protected function onError($error, &$clientSocket) {
        
    }
	
	/*protected function logServidor($text) {
		
	}*/
	
	private function iniciaConexao() {
		$this->ssh = new Net_SSH2(SERVER);
		if (!$this->ssh->login(USER, PASS)) {
			exit('Login Failed');
		}
		$this->mens ( $this->readTerminal());
		//$this->ssh->enablePTY(); 
	}
	
	private function enviaComando($comando) {
		if (substr_compare($comando, "sudo", 0, 4)) {
			//$this->mens ($this->readTerminal());
			$this->ssh->write($comando . "\n");
			$this->mens( $this->formataSaida ($this->readTerminal() ));
			//$this->mens( $this->ssh->exec($comando));
		}
		else {
			//$this->mens($this->readTerminal() );
			$this->ssh->write($comando . "\n");
			$output = $this->ssh->read('#[pP]assword[^:]*:|'. USER .'[@'. SERVER .']?:~\$#', NET_SSH2_READ_REGEX);
			$this->mens ($output);
			if (preg_match('#[pP]assword[^:]*:#', $output)) {
				$this->ssh->write(PASS ."\n");
			}
			$this->mens ($this->readTerminal());
		}	
	}
	
	private function getCorCodigos ($codigo) {
		switch ($codigo) {
			case "1":
			case "01": 
				return "#000000";
			case "30":
				return "#00ff00";
			case "31":
				return "#ff0000";
			case "32":
				return "#00ff00";
			case "33":
				return "#ffff00";
			case "34":
				return "#0000ff";
			case "35":
				return "#ff00ff";
			case "36":
				return "#00ffff";
			case "37":
				return "#ff0000";
			case "40":
				return "#000000";
			case "41": 
				return "#ffffff";
			case "42":
			case "43":
				return "#000000";
			
		}
		return "#000000";
	}
	
	private function formataSaida ($saida) {
		//return $saida;
		//$saida = substr($saida,14);	
		
		$this->ansi = new File_ANSI();
		$this->ansi->appendString($saida);
		return $this->ansi->getScreen();
		
		/*$newstring = preg_replace_callback( 
			'/\x{001B}\[\d\d;\d\dm[^\x{001B}]*\x{001B}\[0m/', 
			function ($match) {
				return '<span style="color: '. $this->getCorCodigos(substr($match[0], 5,2)) .'; background-color: '. $this->getCorCodigos(substr($match[0], 2,2)) .'">' . substr( substr($match[0],0,-4), 8) . '</span>';
			},
			$saida 
		); 
		$newstring = preg_replace_callback( 
			'/\x{001B}\[\d\d;\d\d;\d\dm[^\x{001B}]*\x{001B}\[0m/', 
			function ($match) {
				return '<span style="color: '. $this->getCorCodigos(substr($match[0], 5,2)) .'; background-color: '. $this->getCorCodigos(substr($match[0], 2,2)) .'">' . substr( substr($match[0],0,-4), 11) . '</span>';
			},
			$newstring 
		); */
		return preg_replace ('/\x{001B}\[0m/', "", $newstring); 
	}
}

$aa = new WebSocketImpl();

$aa->listen(5555);
