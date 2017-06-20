var Monitoramento;

Monitoramento = (function() {

    //Monitoramento.prototype = new View();

    function Monitoramento(data) {
        //extende(this, View);
        if (typeof data != "object")
            return;
        this.id = data["id"];
        this.nome = data["nome"];
    }

    Monitoramento.prototype.formatar = View.prototype.formatar;
    
    Monitoramento.prototype.htmlTable = View.prototype.htmlTable;
    
    return Monitoramento;
})();