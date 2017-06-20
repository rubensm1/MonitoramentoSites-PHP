<script src="view/maquina/maquina.js"></script>
<div class="panel panel-jquery">
    <div class="panel-heading">Sistema</div>
    <div id="div-sistema-table" class="div-model-table" class="panel-body">
        
    </div>
</div>
<button type="button" onclick="atualizaSistemaTable()">Atualizar</button>
<button id="bt-cadastro" type="button" onclick="$('#sistema-form').dialog('open');">Cadastro</button>
<form id="sistema-form" class="model-form" method="POST" style="display:none;">
    <table class="table table-bordered">
        <tr><td>ID:</td><td><input type="number" name="id" disabled /></td></tr>
        <tr><td>Nome:</td><td><input type="text" name="nome" value="" required /></td></tr>
        <tr><td>URL Produção:</td><td><input type="text" name="urlProducao" value="" /></td></tr>
        <tr><td>URL Homologação:</td><td><input type="text" name="urlHomologacao" value="" /></td></tr>
		<tr>
			<td>Máquina Produção:</td>
			<td><input name="maquinaProducao" value="" disabled /><button id="bt-maquina-producao" class="bt-select-item reduz-button" type="button" onclick="$('#sistema-form').dialog('open');">Pesquisar Máquina de Produção</button></td>
		</tr>
        <tr>
			<td>Máquina Homologação:</td>
			<td><input name="maquinaHomologacao" value="" disabled /><button id="bt-maquina-homologacao" class="bt-select-item reduz-button" type="button" onclick="$('#sistema-form').dialog('open');">Pesquisar Máquina de Homologação</button></td>
		</tr>
    </table>
</form>
<div class="panel panel-jquery" style="display:none;">
    <div id="model-maquinas" class="div-model-table" class="panel-body">
        <table class="table table-bordered">
			<thead>
				<tr>
					<th>Nome</th>
					<th>IP</th>
					<th>Ativa</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($maquinaList)) echo $maquinaList; ?>
			<tbody>
		</table>
    </div>
</div>

<script>
    //var sistema = new Sistema();
    function atualizaSistemaTable(sistemas) {
        if (sistemas == null || (Array.isArray(sistemas) && sistemas.length == 0)) {
            Sistema.lista = sistemas = view.carregarLista(JSON.parse(ajaxPadrao("sistema", "sistemaTable", null)), Sistema.name);
        }
        $("#div-sistema-table").html(new Sistema().htmlTable(sistemas));
        $(".bt-select-item" ).button({icons: {primary: "ui-icon-search"}, text: false});
    }
    Sistema.lista = view.carregarLista(<?php if (isset($sistemaList)) echo $sistemaList; else echo "null"; ?>, Sistema.name);
    atualizaSistemaTable(Sistema.lista);

    $("#sistema-form").dialog({
        title: "Cadastro de Sistemas",
        width: 600,
        height: 400,
        autoOpen: false,
        buttons: [
            {text: "Salvar", width: 100, type:"submit", form: "sistema-form", click: function(){}},
            {text: "Excluir", width: 100, click: function () {ajaxPadrao("sistema", "excluir", {id: parseInt($("#sistema-form").find("[name='id']").val())} ); atualizaSistemaTable();}},
            {text: "Limpar", width: 100, click: function () {$(this)[0].reset();}},
            {text: "Fechar", width: 100, click: function () {$(this).dialog("close");}}
        ]
    });
	
	$("#model-maquinas").dialog({
        title: "Selecionar Máquina",
        width: 600,
        height: 400,
        autoOpen: true
    });
    $("#sistema-form").submit ( function(ev){
        ev.preventDefault();
        if ($("#sistema-form")[0].reportValidity()) {
            var obj = $(this).serializeObject();
            var idImput = $(this).find("[name='id']");
            if (idImput.val())
                obj["id"] = parseInt(idImput.val());
            idImput.val(parseInt (ajaxPadrao("sistema", "salvar", obj ) ) );
            atualizaSistemaTable();
        }
    });
    $("button").button();
	$(".selecionar-maquina-button").button({icons: {primary: "ui-icon-check"}, text: false});
</script>