var Servico;

Servico = (function(){
    
    /**
     * @param int id
     * @param String nome
     * @param String link
     * @param boolean ativa
     */
    function Servico(id, nome, url, ativo) {
        this.id = id;
        this.nome = nome;
        this.url = url;
        this.ativo = ativo;
    }
    
    return Servico;
})();

