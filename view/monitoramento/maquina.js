var Maquina;

Maquina = (function(){
    
    /**
     * @param {int} id
     * @param {String} nome
     * @param {String} endereco
     * @param {String} ip 
     * @param {boolean} ativa
     */
    function Maquina(id, nome, endereco, ip, ativa) {
        this.id = id;
        this.nome = nome;
        this.endereco = endereco;
        this.ip = ip;
        this.ativa = ativa;
    }
    
    return Maquina;
})();


