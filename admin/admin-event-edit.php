<?php
// 1. SETUP AND INITIALIZATION
// =============================================================================

// Garante que a sessão seja iniciada no início do script.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado, redirecionando para o login caso não esteja.
if (!isset($_SESSION["nome_completo"])) {
    header("Location: login.php");
    exit();
}

// Inclui os arquivos necessários e define constantes.
include_once "models/conexao.php";
include_once "models/Eventos.php";

const UPLOAD_DIR = 'uploads/';
const MAX_FILE_SIZE = 3 * 1024 * 1024; // 3 MB
const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/gif'];

$eventos = new Eventos($conexao);
$feedback = null;

// 2. FORM SUBMISSION HANDLING (POST request using PRG pattern)
// =============================================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_VALIDATE_INT);
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $data_evento = trim($_POST['data_evento'] ?? '');
    $local = trim($_POST['local'] ?? '');
    $stato = trim($_POST['stato'] ?? '');

    // Redireciona se o ID for inválido.
    if (!$id_evento) {
        header("Location: admin-event-all.php");
        exit();
    }

    // Busca os dados atuais do evento para obter a imagem antiga.
    $evento_atual_arr = $eventos->read($id_evento);
    $evento_atual = is_array($evento_atual_arr) && count($evento_atual_arr) > 0 ? $evento_atual_arr[0] : null;
    if (!$evento_atual) {
        $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Erro: Evento não encontrado para atualização.'];
        header("Location: admin-event-all.php");
        exit();
    }

    $caminhoImagem = $evento_atual['url_imagem']; // Padrão é a imagem existente.

    // Tratamento do upload de nova imagem.
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem = $_FILES['imagem'];

        // Validação do arquivo no servidor.
        if ($imagem['size'] > MAX_FILE_SIZE) {
            $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Erro: O arquivo excede o tamanho máximo de 3 MB.'];
        } elseif (!in_array(mime_content_type($imagem['tmp_name']), ALLOWED_MIMES)) {
            $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Erro: Tipo de arquivo inválido. Apenas JPG, PNG e GIF são permitidos.'];
        } else {
            // Garante que o diretório de upload exista.
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }

            // Gera um nome de arquivo único e seguro.
            $nomeImagem = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", basename($imagem['name']));
            $novoCaminhoImagem = UPLOAD_DIR . $nomeImagem;

            // Move o novo arquivo.
            if (move_uploaded_file($imagem['tmp_name'], $novoCaminhoImagem)) {
                // Remove a imagem antiga, se existir.
                if (!empty($evento_atual['url_imagem']) && file_exists($evento_atual['url_imagem'])) {
                    unlink($evento_atual['url_imagem']);
                }
                $caminhoImagem = $novoCaminhoImagem; // Atualiza o caminho da imagem para o novo arquivo.
            } else {
                $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Erro ao salvar a nova imagem.'];
            }
        }
    }

    // Se não houve erro de upload, prossegue com a atualização no banco de dados.
    if (!isset($_SESSION['feedback'])) {
        $atualizado = $eventos->update($id_evento, $titulo, $descricao, $data_evento, $local, $caminhoImagem, $stato);

        if ($atualizado) {
            $_SESSION['feedback'] = ['tipo' => 'success', 'mensagem' => 'Evento atualizado com sucesso!'];
            header("Location: admin-event-all.php");
            exit();
        } else {
            $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Erro ao atualizar o evento no banco de dados.'];
        }
    }

    // Em caso de erro de validação ou de banco, redireciona de volta para o formulário de edição.
    header("Location: admin-event-edit.php?id=" . $id_evento);
    exit();
}

// 3. DATA FETCHING FOR PAGE DISPLAY (GET request)
// =============================================================================

// Pega o ID do evento da URL.
$id_evento = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_evento) {
    header("Location: admin-event-all.php");
    exit();
}

// Busca os dados do evento pelo ID.
$evento_arr = $eventos->read($id_evento);
$evento = is_array($evento_arr) && count($evento_arr) > 0 ? $evento_arr[0] : null;

// Se o evento não for encontrado, redireciona com uma mensagem de erro.
if (!$evento) {
    $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Evento não encontrado!'];
    header("Location: admin-event-all.php");
    exit();
}

// Busca a mensagem de feedback da sessão, se houver, e depois a limpa.
$feedback = $_SESSION['feedback'] ?? null;
unset($_SESSION['feedback']);

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
                        <li class="breadcrumb-item"><a href="admin-event-all.php">Lista de Eventos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Evento</li>
                    </ol>
                </nav>

                <!-- Alerta de feedback -->
                <?php if ($feedback): ?>
                <div class="alert alert-<?= htmlspecialchars($feedback['tipo']) ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($feedback['mensagem']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fa fa-pencil me-2" aria-hidden="true"></i>Editar Evento</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="admin-event-edit.php" enctype="multipart/form-data">
                            <input type="hidden" name="id_evento" value="<?= (int)$evento['id'] ?>">
                            
                            <div class="mb-3">
                                <label for="titulo" class="form-label fw-bold">Nome do evento</label>
                                <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($evento['titulo']) ?>" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="descricao" class="form-label fw-bold">Descrição do evento</label>
                                <textarea id="descricao" name="descricao" class="form-control" rows="5" required><?= htmlspecialchars($evento['descricao']) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="data_evento" class="form-label fw-bold">Data e Hora</label>
                                    <input type="datetime-local" id="data_evento" name="data_evento" value="<?= date('Y-m-d\TH:i', strtotime($evento['data_evento'])) ?>" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="local" class="form-label fw-bold">Local</label>
                                    <input type="text" id="local" name="local" value="<?= htmlspecialchars($evento['localizacao']) ?>" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="imagem" class="form-label fw-bold">Alterar Imagem do Evento</label>
                                <input class="form-control" type="file" id="imagem" name="imagem" accept="image/png, image/jpeg, image/gif">
                                <div class="form-text">O arquivo deve ser uma imagem (JPG, PNG, GIF) com tamanho máximo de 3 MB. Deixe em branco para manter a imagem atual.</div>
                                <script>
                                    document.getElementById('imagem').addEventListener('change', function () {
                                        const file = this.files[0];
                                        if (file && file.size > 3 * 1024 * 1024) { // 3 MB
                                            alert('Erro: O arquivo excede o tamanho máximo de 3 MB.');
                                            this.value = ''; // Limpa o campo
                                        }
                                    });
                                </script>
                            </div>

                            <?php if (!empty($evento['url_imagem']) && file_exists($evento['url_imagem'])): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Imagem Atual</label><br>
                                <img src="<?= htmlspecialchars($evento['url_imagem']) ?>?t=<?= time() ?>" alt="Imagem atual" class="img-thumbnail" style="width: 150px; height: auto; border-radius: 8px;">
                            </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="stato" class="form-label fw-bold">Status</label>
                                <select id="stato" name="stato" class="form-select" required>
                                    <option value="Ativo" <?= $evento['stato'] === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                                    <option value="Inativo" <?= $evento['stato'] === 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                                </select>
                            </div>

                            <button type="submit" name="editar" class="btn btn-primary btn-sm">
                                <i class="fa fa-floppy-o me-2" aria-hidden="true"></i>Atualizar Evento
                            </button>
                            <a href="admin-event-all" class="btn btn-secondary btn-sm">Cancelar</a>
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
