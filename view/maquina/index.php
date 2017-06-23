<div class="panel panel-jquery">
    <div class="panel-heading">Maquina</div>
    <div id="div-maquina-table" class="div-model-table" class="panel-body">
        
    </div>
</div>
<button type="button" onclick="atualizaMaquinaTable()">Atualizar</button>
<button id="bt-cadastro" type="button" onclick="$('#maquina-form').dialog('open');">Cadastro</button>
<form id="maquina-form" class="model-form" method="POST" style="display:none;">
    <table class="table table-bordered">
        <tr><td>ID:</td><td><input type="number" name="id" disabled /></td></tr>
        <tr><td>nome:</td><td><input type="text" name="nome" value="" required /></td></tr>
        <tr><td>ip:</td><td><input type="text" name="ip" value="" required /></td></tr>
        <tr><td>usuarioAcesso:</td><td><input type="text" name="usuarioAcesso" value=""  /></td></tr>
        <tr><td>senhaAcesso:</td><td><input type="text" name="senhaAcesso" value=""  /></td></tr>
        <tr><td>dataRegistro:</td><td><input type="date" name="dataRegistro" value="2017-06-12" required /></td></tr>
        <tr><td>semDNS:</td><td><input type="checkbox" name="semDNS" value="false" onchange="this.value = this.checked" /></td></tr>
        <tr><td>ativa:</td><td><input type="checkbox" name="ativa" value="false" onchange="this.value = this.checked" /></td></tr>
    </table>
</form>

<script>
    //var maquina = new Maquina();
    function atualizaMaquinaTable(maquinas) {
        if (maquinas == null || (Array.isArray(maquinas) && maquinas.length == 0)) {
            Maquina.lista = maquinas = view.carregarLista(JSON.parse(ajaxPadrao("maquina", "maquinaTable", null)), Maquina.name);
        }
        $("#div-maquina-table").html(new Maquina().htmlTable(maquinas));
        $(".bt-select-item" ).button({icons: {primary: "ui-icon-check"}, text: false});
    }
    Maquina.lista = view.carregarLista(<?php if (isset($maquinaList)) echo $maquinaList; else echo "null"; ?>, Maquina.name);
    atualizaMaquinaTable(Maquina.lista);

    $("#maquina-form").dialog({
        title: "Cadastro de Maquinas",
        width: 600,
        height: 400,
        autoOpen: false,
        buttons: [
            {text: "Salvar", width: 100, type:"submit", form: "maquina-form", click: function(){}},
            {text: "Excluir", width: 100, click: function () {ajaxPadrao("maquina", "excluir", {id: parseInt($("#maquina-form").find("[name='id']").val())} ); atualizaMaquinaTable();}},
            {text: "Limpar", width: 100, click: function () {$(this)[0].reset();}},
            {text: "Fechar", width: 100, click: function () {$(this).dialog("close");}}
        ]
    });
    $("#maquina-form").submit ( function(ev){
        ev.preventDefault();
        if ($("#maquina-form")[0].reportValidity()) {
			var checksFalse = $(this).find(":checkbox[value=false]");
			$(checksFalse).prop("checked",true);
            var obj = $(this).serializeObject();
			$(checksFalse).prop("checked",false);
            var idImput = $(this).find("[name='id']");
            if (idImput.val())
                obj["id"] = parseInt(idImput.val());
            idImput.val(parseInt (ajaxPadrao("maquina", "salvar", obj ) ) );
            atualizaMaquinaTable();
        }
    });
    $("button").button();
</script>