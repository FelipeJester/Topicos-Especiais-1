<?php

namespace App\Core;

use PDO;
use PDOException;

class Model
{
    protected static $pdo;

    public function __construct()
    {
        if (self::$pdo === null) {
            $this->connect();
        }
    }

    protected function connect()
    {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Em caso de erro de conexão, exibe a mensagem (apenas em modo debug)
            if (DEBUG_MODE) {
                die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
            } else {
                die("Erro de Conexão com o Banco de Dados. Tente novamente mais tarde.");
            }
        }
    }

    /**
     * Executa uma query de seleção.
     * @param string $sql
     * @param array $params
     * @return array
     */
    protected function query(string $sql, array $params = []): array
    {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Executa uma query de inserção, atualização ou exclusão.
     * @param string $sql
     * @param array $params
     * @return int|bool Retorna o ID da última inserção ou true/false para outras operações.
     */
    protected function execute(string $sql, array $params = []): int|bool
    {
        $stmt = self::$pdo->prepare($sql);
        $result = $stmt->execute($params);

        if (str_starts_with(trim(strtolower($sql)), 'insert')) {
            // Retorna o ID da última inserção
            return self::$pdo->lastInsertId();
        }

        return $result;
    }
}
