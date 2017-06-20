var Mensagem;

Mensagem = (function() {

    /**
     * @param String type
     * @param String classe
     * @param Object dados
     * @param String erro
     */
    function Mensagem(type, classe, dados, erro) {
        if (classe == null && dados == null && erro == null) {
            var obj = (typeof type == "string" ? JSON.parse(type) : type);
            type = obj.type;
            classe = obj.classe;
            dados = obj.dados;
            erro = obj.erro;
        }
        this.type = type;
        this.classe = classe;
        this.dados = dados;
        this.erro = erro;
    }

    return Mensagem;
})();
