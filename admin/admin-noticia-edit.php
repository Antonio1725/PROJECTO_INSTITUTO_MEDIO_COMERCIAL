<?php
// Garante que a sessão seja iniciada no início do script, antes de qualquer saída HTML.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
if (!isset($_SESSION["nome_completo"])) {
    header("Location: login.php");
    exit();
}

// Inclui os arquivos necessários.
include_once "models/conexao.php";
include_once "models/Noticias.php";

// Variável para armazenar feedback (erros ou sucesso) para o usuário.
$feedback = null;

// 1. VALIDAÇÃO DO ID
// Verifica se o ID da notícia foi fornecido via GET. Se não, redireciona com erro.
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'ID da notícia inválido ou não fornecido.'];
    header("Location: admin-noticia-all.php");
    exit();
}
$id = intval($_GET['id']);

// 2. RECUPERAÇÃO DOS DADOS EXISTENTES
// Busca a notícia no banco de dados para preencher o formulário.
$stmt = $conexao->prepare("SELECT id, titulo, conteudo, url_imagem, stato FROM noticias WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Notícia não encontrada.'];
    header("Location: admin-noticia-all.php");
    exit();
}
$noticia = $result->fetch_assoc();
$stmt->close();

// 3. PROCESSAMENTO DO FORMULÁRIO (MÉTODO POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e limpa os dados do formulário.
    $titulo = trim($_POST['titulo']);
    $conteudo = trim($_POST['conteudo']);
    $stato = $_POST['stato'];
    $url_imagem = $noticia['url_imagem']; // Mantém a imagem atual por padrão.

    // Validação básica dos campos.
    if (empty($titulo) || empty($conteudo)) {
        $feedback = ['tipo' => 'danger', 'mensagem' => 'Os campos Título e Descrição são obrigatórios.'];
    } else {
        // Processamento do upload de nova imagem, se houver.
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $imagem = $_FILES['imagem'];
            $permitidos = ['image/jpeg', 'image/png', 'image/jpg'];
            $max_size = 3 * 1024 * 1024; // 3 MB

            if (!in_array($imagem['type'], $permitidos)) {
                $feedback = ['tipo' => 'danger', 'mensagem' => 'Tipo de arquivo não permitido. Apenas JPG, JPEG e PNG são aceitos.'];
            } elseif ($imagem['size'] > $max_size) {
                $feedback = ['tipo' => 'danger', 'mensagem' => 'O tamanho da imagem não pode exceder 3 MB.'];
            } else {
                $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
                $novo_nome = uniqid('noticia_', true) . '.' . $extensao;
                $caminho_upload = 'uploads/' . $novo_nome;

                if (move_uploaded_file($imagem['tmp_name'], $caminho_upload)) {
                    // Apaga a imagem antiga se o upload da nova for bem-sucedido.
                    if (!empty($noticia['url_imagem']) && file_exists($noticia['url_imagem'])) {
                        unlink($noticia['url_imagem']);
                    }
                    $url_imagem = $caminho_upload; // Define o caminho da nova imagem.
                } else {
                    $feedback = ['tipo' => 'danger', 'mensagem' => 'Erro ao fazer upload da nova imagem.'];
                }
            }
        }

        // Se não houver erros de validação ou upload, atualiza o banco de dados.
        if ($feedback === null) {
            $noticias_model = new Noticias($conexao);
            $sucesso = $noticias_model->update($id, $titulo, $conteudo, $url_imagem, $stato);

            if ($sucesso) {
                $_SESSION['feedback'] = ['tipo' => 'success', 'mensagem' => 'Notícia atualizada com sucesso!'];
                header("Location: admin-noticia-all.php");
                exit();
            } else {
                $feedback = ['tipo' => 'danger', 'mensagem' => 'Erro ao atualizar a notícia no banco de dados.'];
            }
        }
    }

    // Se houver erro, mantém os dados que o usuário digitou no formulário.
    $noticia['titulo'] = $titulo;
    $noticia['conteudo'] = $conteudo;
    $noticia['stato'] = $stato;
}

// Inclui o cabeçalho da página HTML.
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
                        <li class="breadcrumb-item"><a href="admin-noticia-all.php">Lista de Notícias</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Notícia</li>
                    </ol>
                </nav>

                <?php
                // Exibe a mensagem de feedback (erro) se houver.
                if (isset($feedback)) {
                    echo '<div class="alert alert-' . htmlspecialchars($feedback['tipo']) . ' alert-dismissible fade show" role="alert">';
                    echo htmlspecialchars($feedback['mensagem']);
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                }
                ?>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fa fa-pencil me-2" aria-hidden="true"></i> Editar Notícia</h4>
                    </div>
                    <div class="card-body">
                        <form enctype="multipart/form-data" method="post" action="admin-noticia-edit.php?id=<?= $id ?>">
                            <div class="mb-3">
                                <label for="titulo" class="form-label fw-bold">Nome da notícia</label>
                                <input type="text" name="titulo" id="titulo" class="form-control" value="<?= htmlspecialchars($noticia['titulo']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="conteudo" class="form-label fw-bold">Descrição da notícia</label>
                                <textarea name="conteudo" id="conteudo" rows="5" class="form-control" required><?= htmlspecialchars($noticia['conteudo']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Imagem Atual</label><br>
                                <?php if (!empty($noticia['url_imagem']) && file_exists($noticia['url_imagem'])): ?>
                                    <img src="<?= htmlspecialchars($noticia['url_imagem']); ?>" alt="Imagem atual" class="img-thumbnail" style="max-width: 200px;">
                                <?php else: ?>
                                    <p class="text-muted">Nenhuma imagem cadastrada.</p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="imagem" class="form-label fw-bold">Alterar Imagem (Opcional)</label>
                                <input class="form-control" name="imagem" type="file" id="imagem" accept=".jpg, .jpeg, .png">
                                <div class="form-text">O arquivo deve ser uma imagem (JPG, JPEG, PNG) com tamanho máximo de 3 MB.</div>
                            </div>

                            <div class="mb-3">
                                <label for="stato" class="form-label fw-bold">Status</label>
                                <select name="stato" id="stato" class="form-select" required>
                                    <option value="Ativo" <?= ($noticia['stato'] == 'Ativo') ? 'selected' : ''; ?>>Ativo</option>
                                    <option value="Inativo" <?= ($noticia['stato'] == 'Inativo') ? 'selected' : ''; ?>>Inativo</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-floppy-o me-2" aria-hidden="true"></i>Atualizar
                            </button>
                            <a href="admin-noticia-all.php" class="btn btn-secondary">
                                <i class="fa fa-times me-2" aria-hidden="true"></i>Cancelar
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- SweetAlert para validação de cliente -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('imagem').addEventListener('change', function () {
        const file = this.files[0];
        if (file && file.size > 3 * 1024 * 1024) { // 3 MB em bytes
            Swal.fire({
                title: 'Arquivo Muito Grande!',
                text: 'O arquivo excede o tamanho máximo de 3 MB. Por favor, escolha um arquivo menor.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            this.value = ''; // Limpa o campo de seleção de arquivo.
        }
    });
</script>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Custom JS -->
<script src="js/custom.js"></script>

</body>
</html>
