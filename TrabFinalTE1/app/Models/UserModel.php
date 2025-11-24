<?php

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    protected $table = 'usuarios';

    /**
     * Busca um usuário pelo email.
     * @param string $email
     * @return object|null
     */
    public function findByEmail(string $email): ?object
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $result = $this->query($sql, ['email' => $email]);

        // Retorna o primeiro resultado como objeto, ou null se não encontrado
        return $result ? (object) $result[0] : null;
    }

    /**
     * Verifica as credenciais do usuário.
     * @param string $email
     * @param string $senha
     * @return object|null
     */
    public function authenticate(string $email, string $senha): ?object
    {
        $user = $this->findByEmail($email);

        if ($user) {
            // Como a senha no init.sql é 'admin' sem hash, faremos uma comparação simples.
            // Em um projeto real, usaríamos password_verify($senha, $user->senha).
            // Para este projeto, vamos manter a simplicidade conforme o init.sql.
            if ($senha === $user->senha) {
                return $user;
            }
        }

        return null;
    }
}
