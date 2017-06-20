SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET NAMES 'utf8';

CREATE TABLE Maquina (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(64) NOT NULL,
    ip VARCHAR(64) NOT NULL,
    usuarioAcesso VARCHAR(64),
    dataRegistro DATE NOT NULL,
    semDNS BOOLEAN NOT NULL,
    ativa BOOLEAN NOT NULL
);

CREATE TABLE Sistema (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(64) NOT NULL,
    urlProducao VARCHAR(64),
    urlHomologacao VARCHAR(64),
    maquinaProducao INT,
	maquinaHomologacao INT,
	FOREIGN KEY (maquinaProducao) REFERENCES Maquina(id) ON UPDATE CASCADE ON DELETE SET NULL,
	FOREIGN KEY (maquinaHomologacao) REFERENCES Maquina(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Implantacao (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sistema INT NOT NULL,
    solicitante VARCHAR(64) NOT NULL,
    versao VARCHAR(64) NOT NULL,
    revisaoSVN VARCHAR(64) NOT NULL,
    data DATE NOT NULL,
    maquina INT NOT NULL,
    ambiente ENUM('Homologação','Produção') NOT NULL,
    tarefas VARCHAR(64),
    comentarios VARCHAR(64),
	FOREIGN KEY (sistema) REFERENCES Sistema(id) ON UPDATE CASCADE
)

CREATE TABLE Comando (
    id INT PRIMARY KEY AUTO_INCREMENT,
    descricao VARCHAR(64) NOT NULL,
    sistema_id INT NOT NULL,
    maquina_id INT NOT NULL,
    ambiente ENUM('Homologação','Produção') NOT NULL,
    instrucao TEXT NOT NULL
);