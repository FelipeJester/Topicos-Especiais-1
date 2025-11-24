-- Script de inicialização para o banco de dados PostgreSQL

-- Tabela de usuários para o sistema de login
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de carros para o CRUD principal
CREATE TABLE carros (
    id SERIAL PRIMARY KEY,
    marca VARCHAR(100) NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    ano INT NOT NULL,
    cor VARCHAR(50),
    preco DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela para armazenar as fotos dos carros
-- Relacionamento de 1 para N com a tabela de carros
CREATE TABLE fotos_carros (
    id SERIAL PRIMARY KEY,
    carro_id INT NOT NULL,
    caminho_foto VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (carro_id) REFERENCES carros(id) ON DELETE CASCADE
);

-- Inserir um usuário padrão para testes
-- A senha é 'admin' (sem hash, para simplificar o início)
-- Em um ambiente de produção, a senha deve ser armazenada com hash
INSERT INTO usuarios (nome, email, senha) VALUES ('Administrador', 'admin@concessionaria.com', 'admin');
