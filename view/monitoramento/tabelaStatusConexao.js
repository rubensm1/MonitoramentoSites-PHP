var TabelaStatusConexao;

TabelaStatusConexao = (function() {

    var CLASSES_ESTILO_LINHA = {PAULEADO: "pauleado", AGUARDANDO: "aguardando", OK: ["ok0", "ok1", "ok2", "ok3", "ok4", "ok5"]};
    
    var TR_NODES = ["checkBox","tdId", "tdNome", "aLink", "tdStatusDescricao", "tdStatusDelay"];
    var TR_NODES_REVERSE = (function(){
        var ob = new Object();
        for (var i in TR_NODES) {
            ob[TR_NODES[i]] = i;
        }
        return ob;
    })();

    var sortConfig = {
        coluna: "tdId",
        asc: true,
        autoRefresh: false,
        compareInt: function(p1, p2) {
            if (p1 > p2)
                return 1;
            else if (p1 < p2)
                return -1;
            else
                return 0;
        },
        compareString: function(p1, p2) {
            return sortConfig.asc ? p1.valor.localeCompare(p2.valor) : (-p1.valor.localeCompare(p2.valor));
        },
        sortFunctionId: function(p1, p2) {
            return sortConfig.asc ? sortConfig.compareInt(p1.chave, p2.chave) : (-sortConfig.compareInt(p1.chave, p2.chave));
        },
        sortFunctionStatusDescricao: function(p1, p2) {
            if (sortConfig.asc) {
                switch (p1.valor) {
                    case "OK":
                        if (p2.valor === "OK")
                            return (p1.valor2.localeCompare(p2.valor2));
                        return -1;
                        break;
                    case "Conectando...":
                        if (p2.valor === "Conectando...")
                            return (p1.valor2.localeCompare(p2.valor2));
                        if (p2.valor === "")
                            return -1;
                        return 1;
                        break;
                    case "":
                        if (p2.valor === "")
                            return (p1.valor2.localeCompare(p2.valor2));
                        return 1;
                        break;
                    default:
                        if (p2.valor === "OK")
                            return 1;
                        if (p2.valor === "Conectando..." || p2.valor === "")
                            return -1;
                        return (p1.valor2.localeCompare(p2.valor2));
                        break;
                }
            }
            else {
                switch (p1.valor) {
                    case "OK":
                        if (p2.valor === "")
                            return -1;
                        if (p2.valor === "OK")
                            return (p1.valor2.localeCompare(p2.valor2));
                        return 1;
                        break;
                    case "Conectando...":
                        if (p2.valor === "Conectando...")
                            return (p1.valor2.localeCompare(p2.valor2));
                        return -1;
                        break;
                    case "":
                        if (p2.valor === "")
                            return (p1.valor2.localeCompare(p2.valor2));
                        return 1;
                        break;
                    default:
                        if (p2.valor === "Conectando...")
                            return 1;
                        if (p2.valor === "OK" || p2.valor === "")
                            return -1;
                        return (p1.valor2.localeCompare(p2.valor2));
                        break;
                }
            }
        },
        sortFunctionStatusDelay: function(p1, p2) {
            if (sortConfig.asc) {
                switch (p1.valor2) {
                    case "OK":
                        if (p2.valor2 === "OK") {
                            if (p1.valor > p2.valor)
                                return 1;
                            else if (p1.valor < p2.valor)
                                return -1;
                            else
                                return 0;
                        }
                        return -1;
                        break;
                    case "Conectando...":
                        if (p2.valor2 === "Conectando...")
                            return (p1.valor3.localeCompare(p2.valor3));
                        if (p2.valor2 === "")
                            return -1;
                        return 1;
                        break;
                    case "":
                        if (p2.valor2 === "")
                            return (p1.valor3.localeCompare(p2.valor3));
                        return 1;
                        break;
                    default:
                        if (p2.valor2 === "OK")
                            return 1;
                        if (p2.valor2 === "Conectando..." || p2.valor2 === "")
                            return -1;
                        return (p1.valor3.localeCompare(p2.valor3));
                        break;
                }
            }
            else {
                switch (p1.valor2) {
                    case "OK":
                        if (p2.valor2 === "")
                            return -1;
                        if (p2.valor2 === "OK") {
                            if (p1.valor > p2.valor)
                                return -1;
                            else if (p1.valor < p2.valor)
                                return 1;
                            else
                                return 0;
                        }
                        return 1;
                        break;
                    case "Conectando...":
                        if (p2.valor2 === "Conectando...")
                            return (p1.valor2.localeCompare(p2.valor3));
                        return -1;
                        break;
                    case "":
                        if (p2.valor2 === "")
                            return (p1.valor2.localeCompare(p2.valor3));
                        return 1;
                        break;
                    default:
                        if (p2.valor === "Conectando...")
                            return 1;
                        if (p2.valor2 === "OK" || p2.valor2 === "")
                            return -1;
                        return (p1.valor2.localeCompare(p2.valor3));
                        break;
                }
            }
        }
    };

    /**
     * Objeto para gerenciar o Status das conexões recebidas do servidor
     * @param {String} t 
     */
    function TabelaStatusConexao(t) {
        var tipo = t;
        this.getTipo = function () {return tipo;};
        var listaIDs = this.obtemIDs();
        this.listaLinhasConexoes = [];
        for (var i in listaIDs) {
            this.addLinha(listaIDs[i]);
        }
    }

    /**
     * Obtem a lista de ids da tabela
     * @returns Array
     */
    TabelaStatusConexao.prototype.obtemIDs = function() {
        var listaIDs = [];
        $("table.tabela-monitoramento tr td:nth-child(2)").each(
                function(index) {
                    listaIDs.push(parseInt($(this).html()));
                }
        );
        return listaIDs;
    };

    /**
     * Adiciona uma linha ao mapa de linhas
     * @param {Number} id
     * @returns {boolean}
     */
    TabelaStatusConexao.prototype.addLinha = function(id) {
        var linha = this.criaLinha(id);
        if (linha !== null) {
            this.listaLinhasConexoes[id] = linha;
            return true;
        }
        else
            return false;
    };

    /**
     * elementos para a desctição do status de uma linha da tabela
     * @param {Number} id       
     * @returns Object       
     */
    TabelaStatusConexao.prototype.criaLinha = function(id) {
        var linha = new Object();
        linha.tr = $("table.tabela-monitoramento tr:has(input#linha" + id + ")");
        if (linha.tr.size() === 0)
            return null;
        if (this.getTipo() === "local")
        {
            linha.checkBox = $(linha.tr[0]).find(":checkbox");//PF("chk" + id);
            linha.tdId = $(linha.tr[0]).find("td:eq(1)");
            linha.tdNome = $(linha.tr[0]).find("td:eq(2)");
            linha.aLink = $(linha.tr[0]).find("td:eq(3) a");
            linha.tdStatusDescricao = $(linha.tr[0]).find("td:eq(4)");
            linha.tdStatusDelay = $(linha.tr[0]).find("td:eq(5)");
            linha.classeEstilo = null;
            return linha;
        }
        else if (this.getTipo() === "maquina")
        {
            linha.checkBox = $(linha.tr[0]).find(":checkbox");//PF("chk" + id);
            linha.tdId = $(linha.tr[0]).find("td:eq(1)");
            linha.tdNome = $(linha.tr[0]).find("td:eq(2)");
            linha.aLink = $(linha.tr[0]).find("td:eq(3)");
            linha.ip = $(linha.tr[0]).find("td:eq(4)");
            linha.tdStatusDescricao = $(linha.tr[0]).find("td:eq(5)");
            linha.tdStatusDelay = $(linha.tr[0]).find("td:eq(6)");
            linha.classeEstilo = null;
            return linha;
        }
    };

    /**
     * Atualiza as colunas de status da interface
     * @param {StatusConexao} statusConexao
     * @returns {void}
     */
    TabelaStatusConexao.prototype.atualizaStatus = function(statusConexao) {
        this.listaLinhasConexoes[statusConexao.id].tdStatusDescricao.html(statusConexao.descricao).attr('title', statusConexao.descricao);
        //this.listaLinhasConexoes[statusConexao.id].tdStatusDelay.html(statusConexao.delay);

        switch (statusConexao.descricao) {
            case "OK":
                if (typeof statusConexao.delay !== "number")
                    statusConexao.delay = parseInt(statusConexao.delay);
                if (isNaN(statusConexao.delay)) {
                    this.listaLinhasConexoes[statusConexao.id].tdStatusDelay.html("").attr('title',"");
                    return;
                }
                this.listaLinhasConexoes[statusConexao.id].tdStatusDelay.html(statusConexao.delay).attr('title',statusConexao.delay);
                this.listaLinhasConexoes[statusConexao.id].checkBox.prop("checked", true);
                if (statusConexao.delay <= 300)
                    this.adicionaClasseEstiloLinha(statusConexao.id, CLASSES_ESTILO_LINHA.OK[0]);
                else if (statusConexao.delay <= 750)
                    this.adicionaClasseEstiloLinha(statusConexao.id, CLASSES_ESTILO_LINHA.OK[1]);
                else if (statusConexao.delay <= 1250)
                    this.adicionaClasseEstiloLinha(statusConexao.id, CLASSES_ESTILO_LINHA.OK[2]);
                else if (statusConexao.delay <= 2500)
                    this.adicionaClasseEstiloLinha(statusConexao.id, CLASSES_ESTILO_LINHA.OK[3]);
                else if (statusConexao.delay <= 5000)
                    this.adicionaClasseEstiloLinha(statusConexao.id, CLASSES_ESTILO_LINHA.OK[4]);
                else
                    this.adicionaClasseEstiloLinha(statusConexao.id, CLASSES_ESTILO_LINHA.OK[5]);
                break;
            case "Conectando...":
                this.listaLinhasConexoes[statusConexao.id].tdStatusDelay.html(statusConexao.delay).attr('title',statusConexao.delay);
                this.adicionaClasseEstiloLinha(statusConexao.id, CLASSES_ESTILO_LINHA.AGUARDANDO);
                this.listaLinhasConexoes[statusConexao.id].checkBox.prop("checked", true);
                break;
            case "":
                this.removeClasseEstiloLinha(statusConexao.id, false);
                this.listaLinhasConexoes[statusConexao.id].tdStatusDelay.html("").attr('title',"");
                this.listaLinhasConexoes[statusConexao.id].checkBox.prop("checked", false);
                break;
            default:
                this.listaLinhasConexoes[statusConexao.id].tdStatusDelay.html("").attr('title',"");
                this.adicionaClasseEstiloLinha(statusConexao.id, CLASSES_ESTILO_LINHA.PAULEADO);
                this.listaLinhasConexoes[statusConexao.id].checkBox.prop("checked", true);
                break;
        }
        if (sortConfig.autoRefresh)
            this.sort(statusConexao.id);
    };

    TabelaStatusConexao.prototype.removeClasseEstiloLinha = function(id, force) {
        if (this.listaLinhasConexoes[id] !== undefined) {
            if (force) {
                this.listaLinhasConexoes[id].tr.attr("class", "");
            }
            else {
                if (this.listaLinhasConexoes[id].classeEstilo) {
                    this.listaLinhasConexoes[id].tr.removeClass(this.listaLinhasConexoes[id].classeEstilo);
                }
            }
            this.listaLinhasConexoes[id].classeEstilo = null;
        }
    };

    TabelaStatusConexao.prototype.adicionaClasseEstiloLinha = function(id, classe) {
        if (this.listaLinhasConexoes[id] !== undefined && this.listaLinhasConexoes[id].classeEstilo !== classe) {
            if (this.listaLinhasConexoes[id].classeEstilo)
                this.removeClasseEstiloLinha(id, false);
            this.listaLinhasConexoes[id].tr.addClass(classe);
            this.listaLinhasConexoes[id].classeEstilo = classe;
        }
    };

    TabelaStatusConexao.prototype.habilitarCheckBoxs = function() {
        for (var i in this.listaLinhasConexoes) {
            this.listaLinhasConexoes[i].checkBox.prop('disabled', false);
        }
    };

    TabelaStatusConexao.prototype.desabilitarCheckBoxs = function() {
        for (var i in this.listaLinhasConexoes) {
            this.listaLinhasConexoes[i].checkBox.prop('disabled', true);
        }
    };

    TabelaStatusConexao.prototype.ligarTudo = function() {
        for (var i in this.listaLinhasConexoes) {
            if (!this.listaLinhasConexoes[i].checkBox.prop('disabled') && !this.listaLinhasConexoes[i].checkBox.prop('checked')) {
                this.listaLinhasConexoes[i].checkBox.prop('checked', true);
                this.listaLinhasConexoes[i].checkBox[0].onchange();
            }
        }
    };

    TabelaStatusConexao.prototype.desligarTudo = function() {
        for (var i in this.listaLinhasConexoes) {
            if (this.listaLinhasConexoes[i].checkBox.prop('checked')) {
                this.listaLinhasConexoes[i].checkBox.prop('checked', false);
                this.listaLinhasConexoes[i].checkBox[0].onchange();
            }
        }
    };

    TabelaStatusConexao.prototype.iniciaStatusConexoes = function(ids) {
        for (var i in ids) {
            var status = new StatusConexao(ids[i], "...", "Conectando...");
            this.atualizaStatus(status);
        }
    };

    TabelaStatusConexao.prototype.limparConexoes = function() {
        for (var i in this.listaLinhasConexoes) {
            this.removeClasseEstiloLinha(i, false);
            this.listaLinhasConexoes[i].tdStatusDelay.html("");
            this.listaLinhasConexoes[i].checkBox.prop("checked", false);
            this.listaLinhasConexoes[i].tdStatusDescricao.html("").attr('title', "");
        }
    };

    TabelaStatusConexao.prototype.sort = function(idLinha) {

        var listaSort = [];
        var _listaLinhasConexoes = this.listaLinhasConexoes;

        function reconstroiLinhas(listaOrd) {
            var corpoTabela = $("table.tabela-monitoramento tbody")[0];
            corpoTabela.innerHTML = "";
            for (var i in listaOrd) {
                corpoTabela.appendChild(_listaLinhasConexoes[listaOrd[i].chave].tr[0]);
            }
        }

        function rearanjaLinha(corpoTabela, linha, entrada, ini, fim) {
            var p = ini + Math.floor((fim - ini) / 2);
            var res = sortConfig.sortFunctionStatusDelay(entrada, {chave: parseInt(corpoTabela.childNodes[p].childNodes[TR_NODES_REVERSE["tdId"]].innerHTML), valor: parseInt(corpoTabela.childNodes[p].childNodes[TR_NODES_REVERSE["tdStatusDelay"]].innerHTML), valor2: corpoTabela.childNodes[p].childNodes[TR_NODES_REVERSE["tdStatusDescricao"]].innerHTML, valor3: corpoTabela.childNodes[p].childNodes[TR_NODES_REVERSE["tdNome"]].innerHTML});
            
            if (fim <= ini + 1) {
                if (res < 0)
                    corpoTabela.insertBefore(linha, corpoTabela.childNodes[ini]);
                else
                    corpoTabela.insertBefore(linha, corpoTabela.childNodes.length > ini + 1 ? corpoTabela.childNodes[ini + 1] : null);
            }
            else {
                if (res < 0) {
                    rearanjaLinha(corpoTabela, linha, entrada, ini, p);
                }
                else {
                    rearanjaLinha(corpoTabela, linha, entrada, p + 1, fim);
                }
            }
        }

        switch (sortConfig.coluna) {
            case "tdId":
                for (var i in this.listaLinhasConexoes) {
                    var valor = parseInt(this.listaLinhasConexoes[i][sortConfig.coluna].html());
                    if (isNaN(valor))
                        valor = Number.MIN_VALUE;
                    listaSort.push({chave: valor});
                }
                reconstroiLinhas(listaSort.sort(sortConfig.sortFunctionId));
                break;
            case "tdStatusDelay":
                //if (idLinha === undefined) {
                    for (var i in this.listaLinhasConexoes) {
                        var valor = parseInt(this.listaLinhasConexoes[i][sortConfig.coluna].html());
                        if (isNaN(valor))
                            valor = Number.MAX_VALUE;
                        listaSort.push({chave: parseInt(this.listaLinhasConexoes[i].tdId.html()), valor: valor, valor2: this.listaLinhasConexoes[i].tdStatusDescricao.html(), valor3: this.listaLinhasConexoes[i].tdNome.html()});
                    }
                    reconstroiLinhas(listaSort.sort(sortConfig.sortFunctionStatusDelay));
                /*}
                else {
                    var corpoTabela = $("table.tabela-monitoramento tbody")[0];
                    rearanjaLinha(
                            corpoTabela,
                            corpoTabela.removeChild(this.listaLinhasConexoes[idLinha].tr[0]),
                            {chave: parseInt(this.listaLinhasConexoes[idLinha].tdId.html()), valor: parseInt(this.listaLinhasConexoes[idLinha][sortConfig.coluna].html()), valor2: this.listaLinhasConexoes[idLinha].tdStatusDescricao.html(), valor3: this.listaLinhasConexoes[idLinha].tdNome.html()},
                            0,
                            corpoTabela.childNodes.length);
                }*/

                break;
            case "tdNome":
            case "aLink":
                for (var i in this.listaLinhasConexoes) {
                    listaSort.push({chave: parseInt(this.listaLinhasConexoes[i].tdId.html()), valor: this.listaLinhasConexoes[i][sortConfig.coluna].html()});
                }
                reconstroiLinhas(listaSort.sort(sortConfig.compareString));
                break;
            case "tdStatusDescricao":
                for (var i in this.listaLinhasConexoes) {
                    listaSort.push({chave: parseInt(this.listaLinhasConexoes[i].tdId.html()), valor: this.listaLinhasConexoes[i].tdStatusDescricao.html(), valor2: this.listaLinhasConexoes[i].tdNome.html()});
                }
                reconstroiLinhas(listaSort.sort(sortConfig.sortFunctionStatusDescricao));
                break;
            default:
                console.log("FALHA NO SORT!");
                break;
        }
    };

    TabelaStatusConexao.prototype.selecionaSort = function(coluna, asc) {
        if (typeof coluna === "number")
            coluna = TR_NODES[coluna];
        switch (coluna) {
            case "tdId":
            case "tdNome":
            case "aLink":
                sortConfig.coluna = coluna;
                if (asc !== undefined)
                    sortConfig.asc = asc;
                sortConfig.autoRefresh = false;
                break;
            case "tdStatusDescricao":
            case "tdStatusDelay":
                sortConfig.coluna = coluna;
                if (asc !== undefined)
                    sortConfig.asc = asc;
                sortConfig.autoRefresh = true;
                break;
            default:
                console.log("FALHA NA SELEÇÃO DE MODO DO SORT!");
                break;
        }
        this.sort();
    };

    TabelaStatusConexao.prototype.setSortAscDesc = function(asc) {
        sortConfig.asc = asc;
    };

    return TabelaStatusConexao;
})();

