<?php
// Garante que a sessão seja iniciada no início do script.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui os arquivos essenciais.
require_once "models/conexao.php";
// A classe 'Usuarios' é necessária para o processamento do formulário.
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

// --- PROCESSAMENTO DO FORMULÁRIO ---
$alert = null; // Variável para armazenar mensagens de alerta para o SweetAlert.

// Função auxiliar para definir o alerta a ser exibido na mesma página.
function set_alert($type, $title, $text) {
    global $alert;
    $alert = json_encode(['icon' => $type, 'title' => $title, 'text' => $text, 'html' => $text]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    // Coleta e sanitiza os dados do formulário.
    $nome_completo = trim($_POST['nome_completo'] ?? '');
    $nome_usual = trim($_POST['nome_usual'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'] ?? '';
    $senhaC = $_POST['senhaC'] ?? '';
    $stato = $_POST['stato'] ?? '';
    $nivel_acesso = $_POST['nivel_acesso'] ?? '';
    
    $errors = [];

    // Validação dos campos de texto e seleções.
    if (empty($nome_completo)) $errors[] = "O campo 'Nome Completo' é obrigatório.";
    if (empty($nome_usual)) $errors[] = "O campo 'Nome Usual' é obrigatório.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "O formato do e-mail é inválido.";
    if (empty($stato)) $errors[] = "O campo 'Status' é obrigatório.";
    if (empty($nivel_acesso)) $errors[] = "O campo 'Nível de Acesso' é obrigatório.";
    
    // Validação da senha.
    if (empty($senha)) {
        $errors[] = "O campo 'Senha' é obrigatório.";
    } elseif (strlen($senha) < 8) {
        $errors[] = "A senha deve ter no mínimo 8 caracteres.";
    } elseif ($senha !== $senhaC) {
        $errors[] = "As senhas não coincidem.";
    }

    // Validação do upload da imagem (obrigatória).
    $caminho_imagem = null;
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
            $caminho_imagem = $pasta_upload . $nome_imagem;
        }
    } else {
        $errors[] = "A imagem de perfil é obrigatória.";
    }

    // Verifica se o e-mail já existe no banco de dados, se não houver outros erros.
    if (empty($errors)) {
        $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->fetch()) {
            $errors[] = "Este e-mail já está cadastrado.";
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        set_alert('error', 'Erro de Validação', implode('<br>', $errors));
    } else {
        // Se todas as validações passaram, processa a inserção.
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem)) {
            // Hash da senha para armazenamento seguro.
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

            $usuario = new Usuarios($conexao);
            $sucesso = $usuario->create($nome_completo, $nome_usual, $email, $senha_hash, $nivel_acesso, $caminho_imagem, $stato);

            if ($sucesso) {
                // Define uma mensagem flash para exibir na página de listagem.
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Usuário cadastrado com sucesso.'];
                // Redireciona para a página de listagem para evitar reenvio do formulário (Padrão PRG).
                header('Location: admin_listar_usuario.php');
                exit();
            } else {
                set_alert('error', 'Erro no Banco de Dados', 'Não foi possível cadastrar o usuário. Verifique os logs para mais detalhes.');
                // Se a inserção falhar, remove a imagem que foi salva para não deixar lixo no servidor.
                if (file_exists($caminho_imagem)) {
                    unlink($caminho_imagem);
                }
            }
        } else {
            set_alert('error', 'Erro de Upload', 'Não foi possível salvar a imagem do perfil.');
        }
    }
}

// --- DADOS PARA A PÁGINA ---
// Inclui o cabeçalho da página.
include_once "header.php";

// Otimização para buscar todas as contagens em uma única consulta.
$sql = "
    SELECT 
        (SELECT COUNT(id) FROM matricula) as total_matricula,
        (SELECT COUNT(id) FROM aluno_inscricao) as total_inscricao,
        (SELECT COUNT(id) FROM noticias WHERE stato='Ativo') as total_noticias,
        (SELECT COUNT(id) FROM eventos WHERE stato='Ativo') as total_eventos,
        (SELECT COUNT(id) FROM cursos) as total_cursos
";
$resultado = $conexao->query($sql);
$contagens = $resultado ? $resultado->fetch_assoc() : [];

// Atribui os valores usando o operador de coalescência nula para definir 0 em caso de falha.
$numLinhaMatricula = $contagens['total_matricula'] ?? 0;
$numLinhaAEstudante = $contagens['total_inscricao'] ?? 0;
$numLinhaNoticias = $contagens['total_noticias'] ?? 0;
$numEventos = $contagens['total_eventos'] ?? 0;
$numLinhaCursos = $contagens['total_cursos'] ?? 0;

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
                        <li class="breadcrumb-item active" aria-current="page">Adicionar Novo Usuário</li>
                    </ol>
                </nav>

                <!-- Formulário de Adição de Usuário -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fa fa-user-plus" aria-hidden="true"></i> Adicionar Novo Usuário</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nome_completo" class="form-label fw-bold">Nome Completo</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" id="nome_completo" name="nome_completo" class="form-control" placeholder="Ex: João da Silva" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nome_usual" class="form-label fw-bold">Nome Usual</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-user-circle"></i></span>
                                        <input type="text" id="nome_usual" name="nome_usual" class="form-control" placeholder="Ex: João" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">E-mail</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@dominio.com" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nivel_acesso" class="form-label fw-bold">Nível de Acesso</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-shield"></i></span>
                                        <select id="nivel_acesso" name="nivel_acesso" class="form-select" required>
                                            <option value="" disabled selected>Selecione o nível</option>
                                            <option value="admin">Admin</option>
                                            <option value="normal">Normal</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="senha" class="form-label fw-bold">Senha (mínimo 8 caracteres)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                        <input type="password" id="senha" name="senha" class="form-control" required minlength="8">
                                        <button class="btn btn-outline-secondary" type="button" id="toggleSenha">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="senhaC" class="form-label fw-bold">Confirme sua senha</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                        <input type="password" id="senhaC" name="senhaC" class="form-control" required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleSenhaC">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="url_imagem" class="form-label fw-bold">Imagem de Perfil</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-image"></i></span>
                                        <input name="imagem" type="file" id="url_imagem" class="form-control" accept=".jpg, .jpeg, .png" required>
                                    </div>
                                    <div id="image-error" class="text-danger mt-1 small" style="display: none;"></div>
                                    <div class="form-text">O arquivo deve ser uma imagem (JPG, PNG) com tamanho máximo de 3 MB.</div>
                                    <img id="image-preview" src="#" alt="Pré-visualização da imagem" class="mt-2 rounded" style="display: none; max-width: 150px; max-height: 150px; object-fit: cover;"/>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="stato" class="form-label fw-bold">Status</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                                        <select id="stato" name="stato" class="form-select" required>
                                            <option disabled selected value="">Selecione o status</option>
                                            <option value="Ativo">Ativo</option>
                                            <option value="Inativo">Inativo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-end">
                                <a href="admin_listar_usuario.php" class="btn btn-secondary me-2">
                                    <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                                </a>
                                <button type="submit" name="cadastrar" class="btn btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Cadastrar Usuário
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
<!-- Custom JS -->
<script src="js/custom.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Script de validação e pré-visualização da imagem
    const imageInput = document.getElementById('url_imagem');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            const imageError = document.getElementById('image-error');
            const imagePreview = document.getElementById('image-preview');
            
            // Reset states
            imageError.style.display = 'none';
            imageError.textContent = '';
            imagePreview.style.display = 'none';
            this.classList.remove('is-invalid');

            if (!file) return;

            const maxSize = 3 * 1024 * 1024; // 3 MB
            const allowedTypes = ['image/jpeg', 'image/png'];

            if (file.size > maxSize) {
                imageError.textContent = 'Erro: O arquivo excede o tamanho máximo de 3 MB.';
                imageError.style.display = 'block';
                this.value = '';
                this.classList.add('is-invalid');
                return;
            }
            if (!allowedTypes.includes(file.type)) {
                imageError.textContent = 'Erro: Tipo de arquivo inválido. Apenas JPG e PNG são permitidos.';
                imageError.style.display = 'block';
                this.value = '';
                this.classList.add('is-invalid');
                return;
            }

            // Image preview
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        });
    }

    // Script para mostrar/ocultar senha
    function setupPasswordToggle(inputId, buttonId) {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(buttonId);
        if (passwordInput && toggleButton) {
            toggleButton.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    }
    setupPasswordToggle('senha', 'toggleSenha');
    setupPasswordToggle('senhaC', 'toggleSenhaC');
});
</script>

<!-- Script para exibir alertas do SweetAlert -->
<?php if (isset($alert)): ?>
<script>
    // Garante que o DOM esteja carregado antes de executar o SweetAlert.
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire(<?php echo $alert; ?>);
    });
</script>
<?php endif; ?>

</body>
</html>