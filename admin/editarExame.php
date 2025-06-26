<?php
// Garante que a sessão seja iniciada no início do script.
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
include_once "models/Exames.php";

$exame_model = new Exames($conexao);
$feedback = null;

// Valida o ID do exame da URL.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'ID do exame inválido.'];
    header("Location: admin-exam-all.php");
    exit();
}
$id = (int)$_GET['id'];

// Busca os dados do exame para preencher o formulário.
// ATENÇÃO: read($id) retorna um array de arrays, então precisamos pegar o primeiro elemento.
$dadosExameArr = $exame_model->read($id);
$dadosExame = null;
if (is_array($dadosExameArr) && count($dadosExameArr) > 0) {
    $dadosExame = $dadosExameArr[0];
}

// Se o exame não for encontrado, redireciona para a lista com uma mensagem de erro.
if (!$dadosExame) {
    $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Exame não encontrado.'];
    header("Location: admin-exam-all.php");
    exit();
}

// Processa o formulário quando enviado (método POST).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $curso = $_POST['curso'] ?? '';
    $data = $_POST['data'] ?? '';
    $dataInicio = $_POST['dataInicio'] ?? '';
    $dataFim = $_POST['dataFim'] ?? '';
    $sala = $_POST['sala'] ?? '';
    $status = $_POST['stato'] ?? '';

    // Atualiza o exame com os novos campos
    // Corrigido: Atualiza diretamente no banco de dados, pois o método update do model não suporta todos os campos
    $stmt = $conexao->prepare("UPDATE exames SET nome = ?, curso = ?, data = ?, dataInicio = ?, dataFim = ?, sala = ?, stato = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("sssssssi", $nome, $curso, $data, $dataInicio, $dataFim, $sala, $status, $id);
        if ($stmt->execute()) {
            $_SESSION['feedback'] = ['tipo' => 'success', 'mensagem' => 'Exame atualizado com sucesso!'];
            header("Location: admin-exam-all.php");
            exit();
        } else {
            $feedback = ['tipo' => 'danger', 'mensagem' => 'Erro ao atualizar o exame. Por favor, tente novamente.'];
        }
        $stmt->close();
    } else {
        $feedback = ['tipo' => 'danger', 'mensagem' => 'Erro ao preparar a atualização do exame.'];
    }
    // Atualiza os dados do exame para refletir as alterações no formulário após o POST
    $dadosExame = [
        'nome' => $nome,
        'curso' => $curso,
        'data' => $data,
        'dataInicio' => $dataInicio,
        'dataFim' => $dataFim,
        'sala' => $sala,
        'stato' => $status
    ];
}

// Busca a lista de cursos para o campo de seleção.
$cursos = [];
$cursos_result = mysqli_query($conexao, "SELECT nome FROM cursos ORDER BY nome ASC");
if ($cursos_result) {
    while ($row = mysqli_fetch_assoc($cursos_result)) {
        $cursos[] = $row['nome'];
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
                        <li class="breadcrumb-item"><a href="admin-exam-all.php">Lista de Exames</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Exame</li>
                    </ol>
                </nav>

                <!-- Formulário de edição -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fa fa-pencil" aria-hidden="true"></i> Editar Exame</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($feedback): ?>
                            <div class="alert alert-<?php echo htmlspecialchars($feedback['tipo']); ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($feedback['mensagem']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="editarExame.php?id=<?= $id; ?>">
                            <div class="mb-3">
                                <label for="nome" class="form-label fw-bold">Nome do Exame</label>
                                <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($dadosExame['nome']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="curso" class="form-label fw-bold">Curso</label>
                                <select id="curso" name="curso" class="form-select" required>
                                    <?php foreach ($cursos as $curso_nome): ?>
                                        <option value="<?= htmlspecialchars($curso_nome); ?>" <?= ($curso_nome == $dadosExame['curso']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($curso_nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <?php if (empty($cursos)): ?>
                                        <option value="">Nenhum curso cadastrado</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="data" class="form-label fw-bold">Data</label>
                                    <input type="date" id="data" name="data" class="form-control" value="<?= htmlspecialchars($dadosExame['data']); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="dataInicio" class="form-label fw-bold">Hora de Início</label>
                                    <input type="time" id="dataInicio" name="dataInicio" class="form-control" value="<?= htmlspecialchars($dadosExame['dataInicio']); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="dataFim" class="form-label fw-bold">Hora de Fim</label>
                                    <input type="time" id="dataFim" name="dataFim" class="form-control" value="<?= htmlspecialchars($dadosExame['dataFim']); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="sala" class="form-label fw-bold">Sala</label>
                                <input type="text" id="sala" name="sala" class="form-control" placeholder="Ex: Sala 101" value="<?= htmlspecialchars($dadosExame['sala']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="stato" class="form-label fw-bold">Status</label>
                                <select id="stato" name="stato" class="form-select" required>
                                    <option value="Ativo" <?= ($dadosExame['stato'] == 'Ativo') ? 'selected' : ''; ?>>Ativo</option>
                                    <option value="Inativo" <?= ($dadosExame['stato'] == 'Inativo') ? 'selected' : ''; ?>>Inativo</option>
                                    <option value="Excluir" <?= ($dadosExame['stato'] == 'Excluir') ? 'selected' : ''; ?>>Excluir</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="admin-exam-all.php" class="btn btn-secondary me-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Atualizar Exame</button>
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
