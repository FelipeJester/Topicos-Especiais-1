<?php

namespace App\Core;

class FileUploader
{
    private $errors = [];

    /**
     * Realiza o upload de um arquivo.
     * @param array $file Array do arquivo (ex: $_FILES['foto'])
     * @param string $targetDir Diretório de destino (já deve existir)
     * @param array $allowedMimes Tipos MIME permitidos
     * @param int $maxSize Tamanho máximo permitido em bytes
     * @return string|null O caminho do arquivo salvo ou null em caso de erro.
     */
    public function upload(array $file, string $targetDir, array $allowedMimes, int $maxSize): ?string
    {
        $this->errors = [];

        // 1. Verifica se houve erro no upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->addError("Erro no upload: Código {$file['error']}.");
            return null;
        }

        // 2. Verifica o tamanho do arquivo
        if ($file['size'] > $maxSize) {
            $this->addError("O arquivo é muito grande. Máximo permitido: " . ($maxSize / 1024 / 1024) . "MB.");
            return null;
        }

        // 3. Verifica o tipo MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimes)) {
            $this->addError("Tipo de arquivo não permitido: {$mimeType}. Apenas PNG e JPG são aceitos.");
            return null;
        }

        // 4. Gera um nome de arquivo único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('carro_', true) . '.' . $extension;
        $targetPath = $targetDir . $fileName;

        // 5. Move o arquivo
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $this->addError("Falha ao mover o arquivo para o diretório de destino.");
            return null;
        }

        // Retorna o nome do arquivo salvo (relativo ao diretório de uploads)
        return $fileName;
    }

    /**
     * Retorna os erros de upload.
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    private function addError(string $message)
    {
        $this->errors[] = $message;
    }
}
