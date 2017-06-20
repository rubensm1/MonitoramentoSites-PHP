<?php

/**
 * Controller do Monitoramento
 */
class MonitoramentoController extends Controller {

    var $name = 'monitoramento';
    
    public function index($dados = NULL) {
		$this->constroiTabelaMonitoramento();
    }
	
	private function dadosTabelaMonitoramento() {
		$this->uses("Sistema");
		return Sistema::nativeQuery(
			"SELECT id, nome, urlProducao as url
			FROM Sistema 
			WHERE urlProducao <> '' 
			UNION 
			SELECT ((SELECT MAX(id) FROM Sistema) + ID) AS id, CONCAT(nome,' (H)'), urlHomologacao as url
			FROM sistema 
			WHERE urlHomologacao <> ''"
		);
	}
	
	public function constroiTabelaMonitoramento($dados = NULL) {
		
		$consulta = $this->dadosTabelaMonitoramento();
		
		$saida = "<table class=\"table table-bordered tabela-monitoramento\">";
		//$saida .= "<colgroup><col style=\"width: 37px;\"><col style=\"width: 50px;\"><col style=\"width: 319px;\"><col style=\"width: 660.203px;\"><col style=\"width: 209.797px;\"><col style=\"width: 110px;\"></colgroup>";
		$saida .= "<thead><tr><th></th><th>ID</th><th>Nome Descritivo</th><th>Link</th><th>Status</th><th>Tempo (ms)</th></tr></thead><tbody>";
		foreach ($consulta as $s) {
			$saida .= "<tr class=\"ui-datatable-tr\">" . 
				"<td style=\"text-align: center; width: 37px;\">
					<input type=\"hidden\" id=\"linha$s->id\" />
					<input type=\"checkbox\" onchange=\"cws.setaSistema(new Servico($s->id, '$s->nome', '$s->url', this.checked))\" disabled />
				</td>" .
				"<td style=\"text-align: center;\">$s->id</td>" .
				"<td>$s->nome</td>" .
				"<td>$s->url</td>" .
				"<td style=\"text-align: center;\"></td>" .
				"<td style=\"text-align: center;\"></td>" .
				"</tr>";
		}
		$saida .= "</tbody></table>";
		$this->set("dados", $saida);
	}
}