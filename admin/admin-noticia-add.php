<?php
// Garante que a sessão seja iniciada no início do script.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
if (!isset($_SESSION["nome_completo"])) {
    header("Location: index.php");
    exit(); // Garante que o script pare de ser executado.
}

// Inclui os arquivos necessários.
include_once "models/conexao.php";
include_once "models/Noticias.php";

$feedback = null;

// Processa o formulário quando enviado (método POST).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitiza os dados do formulário.
    $titulo = trim($_POST['titulo'] ?? '');
    $conteudo = trim($_POST['conteudo'] ?? '');
    $stato = $_POST['stato'] ?? 'Inativo';
    $id_usuario = $_SESSION['id_usuario'] ?? 0; // Assumindo que o ID do usuário está na sessão.

    $imagem_path = null;
    $erros = [];
    

    // Validação dos campos
    if (empty($titulo)) {
        $erros[] = "O título da notícia é obrigatório.";
    }
    if (empty($conteudo)) {
        $erros[] = "O conteúdo da notícia é obrigatório.";
    }
    if (empty($id_usuario)) {
        $erros[] = "Erro de autenticação. Por favor, faça login novamente.";
    }

    // Validação e upload da imagem
    if (isset($_FILES['url_imagem']) && $_FILES['url_imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem_info = $_FILES['url_imagem'];
        $tamanho_maximo = 3 * 1024 * 1024; // 3 MB
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/jpg'];
        
        if ($imagem_info['size'] > $tamanho_maximo) {
            $erros[] = "O arquivo excede o tamanho máximo de 3 MB.";
        }
        
        if (!in_array($imagem_info['type'], $tipos_permitidos)) {
            $erros[] = "Formato de arquivo inválido. Apenas JPG, JPEG e PNG são permitidos.";
        }

        if (empty($erros)) {
            $nome_imagem = uniqid() . "_" . basename($imagem_info['name']);
            $destino = "uploads/" . $nome_imagem;

            if (move_uploaded_file($imagem_info['tmp_name'], $destino)) {
                $imagem_path = $destino;
            } else {
                $erros[] = "Ocorreu um erro ao mover o arquivo de imagem.";
            }
        }
    }

    // Se não houver erros, tenta criar a notícia
    if (empty($erros)) {
        $noticia_model = new Noticias($conexao);
        if ($noticia_model->create($titulo, $conteudo, $imagem_path, $id_usuario, $stato)) {
            $_SESSION['feedback'] = ['tipo' => 'success', 'mensagem' => 'Notícia cadastrada com sucesso!'];
            header("Location: admin-noticia-all.php"); // Redireciona para a lista de notícias
            exit();
        } else {
            $feedback = ['tipo' => 'danger', 'mensagem' => 'Erro ao cadastrar a notícia no banco de dados.'];
        }
    } else {
        // Se houver erros de validação, monta a mensagem de feedback
        $feedback = ['tipo' => 'danger', 'mensagem' => implode('<br>', $erros)];
    }
}

// Inclui o cabeçalho da página.
include_once "header.php";
?>

<div class="d-flex">
    <?php include_once "menulateral.php"; ?>
    <div class="flex-grow-1">
        <?php include_once "container_pricipal.php"; ?>
        
        <!-- Conteúdo principal -->
        <main class="p-4" style="background-color: #f8f9fa;">
            <div class="container-fluid">
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-white shadow-sm p-3 rounded-3 mb-4">
                        <li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home" aria-hidden="true"></i> Painel</a></li>
                        <li class="breadcrumb-item"><a href="admin-noticias-all.php">Lista de Notícias</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Adicionar Notícia</li>
                    </ol>
                </nav>

                <!-- Formulário de adição -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fa fa-plus-circle me-2" aria-hidden="true"></i> Adicionar Nova Notícia</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($feedback): ?>
                            <div class="alert alert-<?php echo htmlspecialchars($feedback['tipo']); ?> alert-dismissible fade show" role="alert">
                                <?php echo $feedback['mensagem']; // Mensagem pode conter HTML (<br>), não escapar. ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" enctype="multipart/form-data" action="admin-noticia-add.php">
                            <div class="mb-3">
                                <label for="titulo" class="form-label fw-bold">Título da Notícia</label>
                                <input type="text" id="titulo" name="titulo" class="form-control" value="<?= htmlspecialchars($_POST['titulo'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="conteudo" class="form-label fw-bold">Conteúdo da Notícia</label>
                                <textarea id="conteudo" name="conteudo" class="form-control" rows="5" required><?= htmlspecialchars($_POST['conteudo'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="url_imagem" class="form-label fw-bold">Imagem da Notícia</label>
                                <input class="form-control" type="file" id="url_imagem" name="url_imagem" accept=".jpg, .jpeg, .png">
                                <div class="form-text">O arquivo deve ser uma imagem (JPG, JPEG, PNG) com tamanho máximo de 3 MB.</div>
                            </div>

                            <div class="mb-3">
                                <label for="stato" class="form-label fw-bold">Status</label>
                                <select id="stato" name="stato" class="form-select" required>
                                    <option value="Ativo" <?= (isset($_POST['stato']) && $_POST['stato'] == 'Ativo') ? 'selected' : ''; ?>>Ativo</option>
                                    <option value="Inativo" <?= (isset($_POST['stato']) && $_POST['stato'] == 'Inativo') ? 'selected' : ''; ?>>Inativo</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="admin-noticia-all" class="btn btn-secondary me-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar Notícia</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Custom JS -->
<script src="js/custom.js"></script>

</body>
</html>