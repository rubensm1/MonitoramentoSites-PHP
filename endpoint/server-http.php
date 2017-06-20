<?php

require_once './WebSocket.php';
require_once './WSException.php';
require_once 'WSMensagem.php';
require_once './HTTP.php';

require_once '../common/bd/Connexao.php';
require_once '../model/Model.php';
require_once '../model/Sistema.php';
require_once '../model/SistemaAmbiente.php';
require_once '../model/StatusConexao.php';


class WebSocketImpl extends WebSocket {
	
	private $cl;
	
	private function mens ($m) {
		$this->enviaDadoSocket( new WSMensagem("comando","String",$m), $this->cl);
	}
	

    protected function onMessage($obj, &$clientSocket) {
		$this->cl = $clientSocket;
		//$obj->dados = trim($obj->dados);
		
		switch ($obj->type) {
			case "conect":
				if ($obj->dados->isAtivo())
					$this->iniciaConexao($obj->dados);
				break;
			case "comando":
			    $resp = $this->processa($obj->dados);
				$this->mens($resp);
			case "chat": 
				
				break;
		}
			
    }

    protected function onClose(&$clientSocket) {
        
    }

    protected function onOpen(&$clientSocket) {
        /* enviar comando de aceite ao solicitante */
        $this->enviaDadoSocket(new WSMensagem('login', 'String', 'init'), $clientSocket);
		$this->cl = $clientSocket;
    }

    protected function onError($error, &$clientSocket) {
        
    }
	
	private function processa ($site, $desc = NULL) {
		$tempo = microtime(true);
		$r = new HttpRequest($site);
		$r->headers["Connection"] = "close";
		$r->send() or die("Couldn't send!");
		return ($desc ? ($desc . " ") : "") . $r->getResponseCode() . " ". $r->getResponseMessage() . " " . intval((microtime(true) - $tempo) * 1000) . "\n";
	}
	
	private function processaStatus ($id, $site) {
		$tempo = microtime(true);
		$r = new HttpRequest($site);
		$r->headers["Connection"] = "close";
		$r->send() or die("Couldn't send!");
		return new StatusConexao(array('id' => $id, 'codigo' => $r->getResponseCode(), 'descricao' => $r->getResponseMessage(), 'delay' => intval((microtime(true) - $tempo) * 1000)));
		//return $r->getResponseCode() . " ". $r->getResponseMessage() . " " . intval((microtime(true) - $tempo) * 1000) . "\n";
	}
	
	private function iniciaConexao ($sisAmb) {
		$i = 5;
		while ($i > 0) {
			$status = $this->processaStatus($sisAmb->getId(), $sisAmb->getURL());
			$this->enviaDadoSocket( new WSMensagem("ping","StatusConexao",$status, NULL, TRUE), $this->cl);
			sleep(3);
			$i--;
		}
	}
	
	/*protected function logServidor($text) {
		
	}*/
}

$aa = new WebSocketImpl();

$aa->listen(5555);
