<?php

namespace App\Models;

use App\Core\Model;

class CarroModel extends Model
{
    protected $table = 'carros';

    /**
     * Busca todos os carros.
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        return $this->query($sql);
    }

    /**
     * Busca um carro pelo ID.
     * @param int $id
     * @return object|null
     */
    public function find(int $id): ?object
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $result = $this->query($sql, ['id' => $id]);
        return $result ? (object) $result[0] : null;
    }

    /**
     * Insere um novo carro.
     * @param array $data
     * @return int|bool ID do carro inserido ou false em caso de falha.
     */
    public function create(array $data): int|bool
    {
        $sql = "INSERT INTO {$this->table} (marca, modelo, ano, cor, preco) VALUES (:marca, :modelo, :ano, :cor, :preco)";
        return $this->execute($sql, $data);
    }

    /**
     * Atualiza um carro existente.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET marca = :marca, modelo = :modelo, ano = :ano, cor = :cor, preco = :preco WHERE id = :id";
        $data['id'] = $id;
        return $this->execute($sql, $data);
    }

    /**
     * Exclui um carro.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        // O ON DELETE CASCADE na tabela fotos_carros garante que as fotos sejam excluídas
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }

    // --- Métodos para Fotos ---

    /**
     * Adiciona uma foto a um carro.
     * @param int $carroId
     * @param string $caminhoFoto
     * @return int|bool
     */
    public function addPhoto(int $carroId, string $caminhoFoto): int|bool
    {
        $sql = "INSERT INTO fotos_carros (carro_id, caminho_foto) VALUES (:carro_id, :caminho_foto)";
        return $this->execute($sql, ['carro_id' => $carroId, 'caminho_foto' => $caminhoFoto]);
    }

    /**
     * Busca todas as fotos de um carro.
     * @param int $carroId
     * @return array
     */
    public function getPhotos(int $carroId): array
    {
        $sql = "SELECT * FROM fotos_carros WHERE carro_id = :carro_id ORDER BY id ASC";
        return $this->query($sql, ['carro_id' => $carroId]);
    }

    /**
     * Exclui uma foto.
     * @param int $fotoId
     * @return bool
     */
    public function deletePhoto(int $fotoId): bool
    {
        $sql = "DELETE FROM fotos_carros WHERE id = :id";
        return $this->execute($sql, ['id' => $fotoId]);
    }
}
