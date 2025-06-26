<?php

// Garante que a sessão seja iniciada no início do script.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui os arquivos essenciais.
require_once "models/conexao.php";
require_once "models/Usuarios.php";

// --- VERIFICAÇÃO DE ACESSO ---
// 1. Verifica se o usuário está logado.
if (!isset($_SESSION["nome_completo"])) {
    header("Location: index.php");
    exit();
}
// 2. Verifica se o usuário tem permissão de administrador.
if ($_SESSION['nivel_acesso'] !== 'admin') {
    // Adiciona uma mensagem flash para exibir na página de destino.
    $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Acesso não autorizado.'];
    header("Location: admin.php"); // Redireciona para o painel principal
    exit();
}

// --- LÓGICA DA PÁGINA ---
$alert = null; // Variável para armazenar mensagens de alerta para o SweetAlert.

// Função auxiliar para definir o alerta a ser exibido.
function set_alert($type, $title, $text) {
    global $alert;
    $alert = json_encode(['icon' => $type, 'title' => $title, 'text' => $text, 'html' => $text]);
}

$usuario_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$usuario_id) {
    $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'ID do usuário inválido ou não especificado.'];
    header("Location: admin_listar_usuario.php");
    exit;
}

$usuario = new Usuarios($conexao);
$dados_usuario_array = $usuario->read($usuario_id);

if (empty($dados_usuario_array)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Usuário não encontrado.'];
    header("Location: admin_listar_usuario.php");
    exit;
}
// Pega o primeiro (e único) resultado do array
$dados_usuario = $dados_usuario_array[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitiza os dados do formulário.
    $nome_completo = trim($_POST['nome_completo'] ?? '');
    $nome_usual = trim($_POST['nome_usual'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $stato = $_POST['stato'] ?? '';
    $nivel_acesso = $_POST['nivel_acesso'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $senhaC = $_POST['senhaC'] ?? '';
    
    $errors = [];

    // Validação dos campos.
    if (empty($nome_completo)) $errors[] = "O campo 'Nome Completo' é obrigatório.";
    if (empty($nome_usual)) $errors[] = "O campo 'Nome Usual' é obrigatório.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "O formato do e-mail é inválido.";
    if (empty($stato)) $errors[] = "O campo 'Status' é obrigatório.";
    if (empty($nivel_acesso)) $errors[] = "O campo 'Nível de Acesso' é obrigatório.";
    
    // Validação da senha (somente se uma nova senha for inserida).
    if (!empty($senha)) {
        if (strlen($senha) < 8) {
            $errors[] = "A nova senha deve ter no mínimo 8 caracteres.";
        } elseif ($senha !== $senhaC) {
            $errors[] = "As senhas não coincidem.";
        }
    }

    // Validação do upload da imagem (opcional na edição).
    $caminho_imagem = $dados_usuario['imagem']; // Mantém a imagem antiga por padrão.
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $pasta_upload = 'uploads/';
        $allowed_mime_types = ['image/jpeg', 'image/png'];
        $max_size = 3 * 1024 * 1024; // 3 MB

        $file_mime_type = mime_content_type($_FILES['imagem']['tmp_name']);
        
        if (!in_array($file_mime_type, $allowed_mime_types)) {
            $errors[] = "Tipo de arquivo inválido. Apenas JPG e PNG são permitidos.";
        } elseif ($_FILES['imagem']['size'] > $max_size) {
            $errors[] = "O arquivo excede o tamanho máximo de 3 MB.";
        } else {
            if (!is_dir($pasta_upload)) {
                mkdir($pasta_upload, 0755, true);
            }
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $nome_imagem = uniqid('user_', true) . '.' . strtolower($ext);
            $caminho_imagem_novo = $pasta_upload . $nome_imagem;
        }
    } elseif (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors[] = "Ocorreu um erro no upload da imagem.";
    }

    if (empty($errors)) {
        // Se uma nova imagem foi validada, move o arquivo.
        if (isset($caminho_imagem_novo)) {
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem_novo)) {
                // Opcional: remover a imagem antiga se não for a padrão.
                if (!empty($caminho_imagem) && file_exists($caminho_imagem) && strpos($caminho_imagem, 'default') === false) {
                    unlink($caminho_imagem);
                }
                $caminho_imagem = $caminho_imagem_novo; // Atualiza o caminho da imagem para o novo.
            } else {
                $errors[] = "Falha ao mover o arquivo de imagem.";
            }
        }
    }

    if (empty($errors)) {
        // A senha só é atualizada se uma nova foi fornecida.
        $senha_para_update = !empty($senha) ? $senha : null;

        $resultado = $usuario->update($usuario_id, $nome_completo, $nome_usual, $email, $senha_para_update, $nivel_acesso, $caminho_imagem, $stato);

        if ($resultado) {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Usuário atualizado com sucesso!'];
            header("Location: admin_listar_usuario.php");
            exit();
        } else {
            set_alert('error', 'Erro!', 'Ocorreu um erro ao atualizar o usuário no banco de dados.');
        }
    } else {
        // Se houver erros, prepara a mensagem de alerta.
        $error_html = '<ul class="text-start">';
        foreach ($errors as $error) {
            $error_html .= '<li>' . htmlspecialchars($error) . '</li>';
        }
        $error_html .= '</ul>';
        set_alert('error', 'Erro de Validação', $error_html);
    }
}

// --- DADOS PARA A PÁGINA ---
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
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home" aria-hidden="true"></i> Painel</a></li>
                        <li class="breadcrumb-item"><a href="admin_listar_usuario.php">Usuários</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Usuário</li>
                    </ol>
                </nav>

                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar Usuário: <?= htmlspecialchars($dados_usuario['nome_usual']) ?></h4>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form method="post" enctype="multipart/form-data" novalidate>
                            <div class="row">
                                <!-- Coluna da Imagem -->
                                <div class="col-lg-4 mb-4 mb-lg-0">
                                    <div class="text-center p-3 border rounded-3 bg-light h-100 d-flex flex-column justify-content-center">
                                        <h6 class="mb-3 text-muted fw-bold">IMAGEM DE PERFIL</h6>
                                        <img id="imagePreview" src="<?= htmlspecialchars($dados_usuario['imagem']) ?>" alt="Imagem de Perfil" class="img-thumbnail rounded-circle mb-3 shadow-sm mx-auto" style="width: 150px; height: 150px; object-fit: cover; border-width: 3px;">
                                        <input type="file" id="imagem" name="imagem" accept=".jpg, .jpeg, .png" class="d-none">
                                        <label for="imagem" class="btn btn-primary w-100">
                                            <i class="fa fa-upload" aria-hidden="true"></i> Alterar Imagem
                                        </label>
                                        <div class="form-text mt-2">JPG ou PNG, máx 3MB.</div>
                                    </div>
                                </div>

                                <!-- Coluna dos Dados -->
                                <div class="col-lg-8">
                                    <h5 class="mb-3 fw-bold text-primary">Dados Pessoais</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nome_completo" class="form-label">Nome Completo</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
                                                <input type="text" id="nome_completo" name="nome_completo" value="<?= htmlspecialchars($dados_usuario['nome_completo']) ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nome_usual" class="form-label">Nome Usual</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
                                                <input type="text" id="nome_usual" name="nome_usual" value="<?= htmlspecialchars($dados_usuario['nome_usual']) ?>" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($dados_usuario['email']) ?>" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <h5 class="mb-3 fw-bold text-primary">Acesso e Segurança</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nivel_acesso" class="form-label">Nível de Acesso</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-shield" aria-hidden="true"></i></span>
                                                <select id="nivel_acesso" name="nivel_acesso" class="form-select" required>
                                                    <option value="admin" <?= $dados_usuario['nivel_acesso'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                    <option value="editor" <?= $dados_usuario['nivel_acesso'] == 'editor' ? 'selected' : '' ?>>Editor</option>
                                                    <option value="usuario" <?= $dados_usuario['nivel_acesso'] == 'usuario' ? 'selected' : '' ?>>Usuário</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="stato" class="form-label">Status</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-toggle-on" aria-hidden="true"></i></span>
                                                <select id="stato" name="stato" class="form-select" required>
                                                    <option value="Ativo" <?= $dados_usuario['stato'] == 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                                                    <option value="Inativo" <?= $dados_usuario['stato'] == 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="senha" class="form-label">Nova Senha</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                                <input type="password" id="senha" name="senha" class="form-control" placeholder="Deixe em branco para não alterar">
                                            </div>
                                            <div class="form-text">Mínimo 8 caracteres.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="senhaC" class="form-label">Confirme a Nova Senha</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                                <input type="password" id="senhaC" name="senhaC" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top text-end">
                                <a href="admin_listar_usuario" class="btn btn-secondary me-2">
                                    <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save" aria-hidden="true"></i> Atualizar Usuário
                                </button>
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Custom JS -->
<script>
    // Exibe o alerta do SweetAlert se a variável $alert estiver definida.
    <?php if (isset($alert)): ?>
    Swal.fire(<?= $alert ?>);
    <?php endif; ?>

    // Preview da imagem de perfil
    document.getElementById('imagem').addEventListener('change', function(event) {
        const preview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
</body>
</html>
