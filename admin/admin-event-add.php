<?php
// Garante que a sessão seja iniciada no início do script, antes de qualquer saída HTML.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
if (!isset($_SESSION["nome_completo"]) || !isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Inclui os arquivos necessários.
include_once "models/conexao.php";
include_once "models/Eventos.php";

// Variáveis para armazenar dados do formulário e feedback.
$feedback = null;
$titulo = '';
$descricao = '';
$data_evento = '';
$local = '';
$stato = 'Ativo'; // Valor padrão

// Processamento do formulário quando enviado via POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e limpa os dados do formulário.
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $data_evento = trim($_POST['data_evento'] ?? '');
    $local = trim($_POST['local'] ?? '');
    $stato = $_POST['stato'] ?? 'Ativo';
    $id_usuario = $_SESSION['id_usuario'];
    $url_imagem = '';

    // Validação básica dos campos obrigatórios.
    if (empty($titulo) || empty($data_evento) || empty($local)) {
        $feedback = ['tipo' => 'danger', 'mensagem' => 'Os campos Nome, Data e Local são obrigatórios.'];
    } else {
        // Processamento do upload da imagem.
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $imagem = $_FILES['imagem'];
            $permitidos = ['image/jpeg', 'image/png', 'image/jpg'];
            $max_size = 3 * 1024 * 1024; // 3 MB

            if (!in_array($imagem['type'], $permitidos)) {
                $feedback = ['tipo' => 'danger', 'mensagem' => 'Tipo de arquivo não permitido. Apenas JPG, JPEG e PNG são aceitos.'];
            } elseif ($imagem['size'] > $max_size) {
                $feedback = ['tipo' => 'danger', 'mensagem' => 'O tamanho da imagem não pode exceder 3 MB.'];
            } else {
                // Cria um nome de arquivo único e seguro.
                $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
                $novo_nome = uniqid('evento_', true) . '.' . $extensao;
                $caminho_upload = 'uploads/' . $novo_nome;

                // Garante que o diretório de upload exista.
                if (!is_dir('uploads/')) {
                    mkdir('uploads/', 0777, true);
                }

                if (move_uploaded_file($imagem['tmp_name'], $caminho_upload)) {
                    $url_imagem = $caminho_upload;
                } else {
                    $feedback = ['tipo' => 'danger', 'mensagem' => 'Erro ao fazer upload da imagem.'];
                }
            }
        } else {
            $feedback = ['tipo' => 'danger', 'mensagem' => 'A imagem do evento é obrigatória.'];
        }

        // Se não houver erros de validação ou upload, insere no banco de dados.
        if ($feedback === null) {
            $eventos_model = new Eventos($conexao);
            $sucesso = $eventos_model->create($titulo, $descricao, $data_evento, $local, $id_usuario, $url_imagem, $stato);

            if ($sucesso) {
                $_SESSION['feedback'] = ['tipo' => 'success', 'mensagem' => 'Evento cadastrado com sucesso!'];
                // Redireciona para a lista de eventos para evitar reenvio do formulário.
                header("Location: admin-event-all.php");
                exit();
            } else {
                $feedback = ['tipo' => 'danger', 'mensagem' => 'Erro ao cadastrar o evento no banco de dados.'];
            }
        }
    }
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
                        <li class="breadcrumb-item"><a href="admin-event-all.php">Lista de Eventos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Adicionar Novo Evento</li>
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
                        <h4 class="mb-0"><i class="fa fa-plus-circle me-2" aria-hidden="true"></i> Adicionar Novo Evento</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" action="admin-event-add.php">
                            <div class="mb-3">
                                <label for="titulo" class="form-label fw-bold">Nome do evento</label>
                                <input type="text" name="titulo" id="titulo" class="form-control" value="<?= htmlspecialchars($titulo); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="descricao" class="form-label fw-bold">Descrição do evento</label>
                                <textarea name="descricao" id="descricao" rows="5" class="form-control"><?= htmlspecialchars($descricao); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="data_evento" class="form-label fw-bold">Data e Hora</label>
                                    <input type="datetime-local" name="data_evento" id="data_evento" class="form-control" value="<?= htmlspecialchars($data_evento); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="local" class="form-label fw-bold">Local</label>
                                    <input type="text" name="local" id="local" class="form-control" value="<?= htmlspecialchars($local); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="imagem" class="form-label fw-bold">Imagem do Evento</label>
                                <input class="form-control" name="imagem" type="file" id="imagem" accept=".jpg, .jpeg, .png" required>
                                <div class="form-text">O arquivo deve ser uma imagem (JPG, JPEG, PNG) com tamanho máximo de 3 MB.</div>
                            </div>

                            <div class="mb-3">
                                <label for="stato" class="form-label fw-bold">Status</label>
                                <select name="stato" id="stato" class="form-select" required>
                                    <option value="Ativo" <?= ($stato == 'Ativo') ? 'selected' : ''; ?>>Ativo</option>
                                    <option value="Inativo" <?= ($stato == 'Inativo') ? 'selected' : ''; ?>>Inativo</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-floppy-o me-2" aria-hidden="true"></i>Cadastrar
                            </button>
                            <a href="admin-event-all.php" class="btn btn-secondary">
                                <i class="fa fa-times me-2" aria-hidden="true"></i>Cancelar
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Inclui o rodapé da página.
include_once "footer.php";
?>
