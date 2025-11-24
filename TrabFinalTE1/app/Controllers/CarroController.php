<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Validator;
use App\Core\FileUploader;
use App\Models\CarroModel;

class CarroController extends Controller
{
    private $carroModel;
    private $validator;
    private $uploader;

    public function __construct()
    {
        parent::__construct();
        $this->carroModel = new CarroModel();
        $this->validator = new Validator();
        $this->uploader = new FileUploader();
    }

    // Ação pública: Listagem de carros
    public function index()
    {
        $carros = $this->carroModel->findAll();
        $data = [
            "title" => "Nossos Carros",
            "carros" => $carros,
            "flash" => $this->getFlashMessage()
        ];
        $this->view("carros/index", $data);
    }

    // Ação protegida: Exibir formulário de criação
    public function create()
    {
        Auth::protect();
        $data = [
            "title" => "Cadastrar Novo Carro",
            "flash" => $this->getFlashMessage(),
            "errors" => $_SESSION['validation_errors'] ?? [],
            "old" => $_SESSION['old_input'] ?? []
        ];
        unset($_SESSION['validation_errors'], $_SESSION['old_input']);

        $this->view("carros/create", $data);
    }

    // Ação protegida: Processar criação (POST)
    public function store()
    {
        Auth::protect();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/carros/create');
            return;
        }

        $data = [
            'marca' => filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING),
            'modelo' => filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING),
            'ano' => filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT),
            'cor' => filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_STRING),
            'preco' => filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT),
        ];

        $rules = [
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'ano' => 'required|numeric|min:1900|max:' . (date('Y') + 1),
            'cor' => 'required|string',
            'preco' => 'required|numeric|min:0',
        ];

        if (!$this->validator->validate($data, $rules)) {
            $_SESSION['validation_errors'] = $this->validator->getErrors();
            $_SESSION['old_input'] = $data;
            $this->setFlashMessage('error', 'Erro de validação. Verifique os campos.');
            $this->redirect('admin/carros/create');
            return;
        }

        // Tenta criar o carro
        $carroId = $this->carroModel->create($data);

        if ($carroId) {
            // Lógica de Upload de Foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadedFileName = $this->uploader->upload(
                    $_FILES['foto'],
                    UPLOAD_DIR,
                    ALLOWED_MIMES,
                    MAX_FILE_SIZE
                );

                if ($uploadedFileName) {
                    $this->carroModel->addPhoto($carroId, $uploadedFileName);
                } else {
                    // Se o upload falhar, registra o erro mas não impede o cadastro do carro
                    $uploadErrors = implode(', ', $this->uploader->getErrors());
                    $this->setFlashMessage('warning', "Carro cadastrado, mas houve um erro no upload da foto: {$uploadErrors}");
                    $this->redirect('admin');
                    return;
                }
            }

            $this->setFlashMessage('success', 'Carro cadastrado com sucesso!');
            $this->redirect('admin');
        } else {
            $this->setFlashMessage('error', 'Erro ao cadastrar o carro no banco de dados.');
            $this->redirect('admin/carros/create');
        }
    }

    // Ação protegida: Exibir formulário de edição
    public function edit($id)
    {
        Auth::protect();
        $carro = $this->carroModel->find($id);

        if (!$carro) {
            $this->setFlashMessage('error', 'Carro não encontrado.');
            $this->redirect('carros');
            return;
        }

        $data = [
            "title" => "Editar Carro: {$carro->modelo}",
            "carro" => $carro,
            "flash" => $this->getFlashMessage(),
            "errors" => $_SESSION['validation_errors'] ?? [],
            "old" => $_SESSION['old_input'] ?? []
        ];
        unset($_SESSION['validation_errors'], $_SESSION['old_input']);

        $this->view("carros/edit", $data);
    }

    // Ação protegida: Processar atualização (POST)
    public function update()
    {
        Auth::protect();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin');
            return;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->setFlashMessage('error', 'ID do carro inválido.');
            $this->redirect('admin');
            return;
        }

        $data = [
            'marca' => filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING),
            'modelo' => filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING),
            'ano' => filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT),
            'cor' => filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_STRING),
            'preco' => filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT),
        ];

        $rules = [
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'ano' => 'required|numeric|min:1900|max:' . (date('Y') + 1),
            'cor' => 'required|string',
            'preco' => 'required|numeric|min:0',
        ];

        if (!$this->validator->validate($data, $rules)) {
            $_SESSION['validation_errors'] = $this->validator->getErrors();
            $_SESSION['old_input'] = $data;
            $this->setFlashMessage('error', 'Erro de validação. Verifique os campos.');
            $this->redirect('admin/carros/edit/' . $id);
            return;
        }

        // Tenta atualizar o carro
        $updated = $this->carroModel->update($id, $data);

        if ($updated) {
            // Lógica de Upload de Foto (se houver)
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadedFileName = $this->uploader->upload(
                    $_FILES['foto'],
                    UPLOAD_DIR,
                    ALLOWED_MIMES,
                    MAX_FILE_SIZE
                );

                if ($uploadedFileName) {
                    // Excluir fotos antigas (opcional, para manter apenas uma foto por carro)
                    $oldPhotos = $this->carroModel->getPhotos($id);
                    foreach ($oldPhotos as $photo) {
                        $filePath = UPLOAD_DIR . $photo['caminho_foto'];
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        $this->carroModel->deletePhoto($photo['id']);
                    }

                    $this->carroModel->addPhoto($id, $uploadedFileName);
                } else {
                    $uploadErrors = implode(', ', $this->uploader->getErrors());
                    $this->setFlashMessage('warning', "Carro atualizado, mas houve um erro no upload da foto: {$uploadErrors}");
                    $this->redirect('admin');
                    return;
                }
            }

            $this->setFlashMessage('success', 'Carro atualizado com sucesso!');
            $this->redirect('admin');
        } else {
            $this->setFlashMessage('error', 'Erro ao atualizar o carro no banco de dados.');
            $this->redirect('admin/carros/edit/' . $id);
        }
    }

    // Ação protegida: Processar exclusão
    public function delete($id)
    {
        Auth::protect();

        // 1. Buscar fotos para exclusão do arquivo
        $photos = $this->carroModel->getPhotos($id);

        // 2. Tenta excluir o carro (e as fotos do DB via CASCADE)
        $deleted = $this->carroModel->delete($id);

        if ($deleted) {
            // 3. Excluir arquivos de fotos do sistema de arquivos
            foreach ($photos as $photo) {
                $filePath = UPLOAD_DIR . $photo['caminho_foto'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->setFlashMessage('success', 'Carro e fotos excluídos com sucesso!');
        } else {
            $this->setFlashMessage('error', 'Erro ao excluir o carro.');
        }

        $this->redirect('carros');
    }
}
