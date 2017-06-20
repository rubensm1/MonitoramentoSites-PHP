/**
 * Objeto para gerenciar o Status das conexões recebidas do servidor
 * @param {Number} id
 * @param {Number} delay
 * @param {String} descricao
 */
var StatusConexao = (function() {

    /**
     * Objeto para gerenciar o Status das conexões recebidas do servidor
     * @param {Number} id
     * @param {Number} delay
     * @param {String} descricao
     */
    function StatusConexao(id, delay, descricao) {
        if (delay == null && descricao == null) {
            var obj = (typeof id == "string" ? JSON.parse(id) : id);
            id = obj.id;
            delay = obj.delay;
            descricao = obj.descricao;
        }
        this.id = id;
        this.delay = delay;
        this.descricao = descricao;
    }
    return StatusConexao;
})();
