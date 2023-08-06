CREATE DATABASE apimysql;

USE apimysql;

CREATE TABLE cadastro (
    cod INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    sobrenome VARCHAR(100),
    idade INT,
    pais VARCHAR(100)
);



