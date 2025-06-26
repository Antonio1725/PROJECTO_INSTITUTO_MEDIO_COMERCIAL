<?php
// Garante que a sessão seja iniciada no início do script, antes de qualquer saída HTML.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
if (!isset($_SESSION["nome_completo"])) {
    header("Location: login.php");
    exit(); // Garante que o script pare de ser executado após o redirecionamento.
}

// Inclui os arquivos necessários.
include_once "models/conexao.php";
include_once "header.php";

$feedback = null; // Para armazenar mensagens de sucesso/erro para o usuário.

// Verifica se o formulário foi submetido via POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {

    // 1. Captura e sanitiza os dados do formulário.
    $nome = trim($_POST['nome'] ?? '');
    $curso = trim($_POST['curso'] ?? '');
    $data = trim($_POST['data'] ?? '');
    $horaInicio = trim($_POST['horaInicio'] ?? '');
    $horaFim = trim($_POST['horaFim'] ?? '');
    $sala = trim($_POST['sala'] ?? '');
    $stato = trim($_POST['stato'] ?? '');
    // Assumindo que o ID do usuário logado está armazenado na sessão.
    $idUsuario = $_SESSION['id_usuario'] ?? null;
    $quantidadeAluno = (int) ($_POST['quantidadeAluno'] ?? 0);

    // 2. Valida o critério para evitar SQL injection na cláusula ORDER BY.
    $criterio_input = $_POST['criterio'] ?? 'desc';
    $criterio = in_array(strtolower($criterio_input), ['asc', 'desc']) ? $criterio_input : 'desc';

    // 3. Validação básica de campos obrigatórios.
    if (empty($nome) || empty($curso) || empty($data) || empty($horaInicio) || empty($horaFim) || empty($sala) || empty($stato) || empty($idUsuario) || $quantidadeAluno <= 0) {
        $feedback = [
            'title' => 'Erro de Validação',
            'text' => 'Por favor, preencha todos os campos obrigatórios corretamente.',
            'icon' => 'warning'
        ];
    } else {
        // 4. Inicia uma transação para garantir a integridade dos dados.
        mysqli_begin_transaction($conexao);

        try {
            // 5. Insere o novo exame usando prepared statements para evitar SQL injection.
            $sql_insert_exame = "INSERT INTO exames(nome, curso, dataInicio, dataFim, sala, idUsuario, stato, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert_exame = mysqli_prepare($conexao, $sql_insert_exame);
            mysqli_stmt_bind_param($stmt_insert_exame, 'sssssiss', $nome, $curso, $horaInicio, $horaFim, $sala, $idUsuario, $stato, $data);

            if (!mysqli_stmt_execute($stmt_insert_exame)) {
                throw new Exception("Erro ao inserir o exame: " . mysqli_stmt_error($stmt_insert_exame));
            }

            $idExame = mysqli_insert_id($conexao);
            mysqli_stmt_close($stmt_insert_exame);

            // 6. Seleciona os alunos que serão associados ao exame.
            // Adicionada condição para selecionar apenas alunos ainda não associados a um exame.
            // A cláusula LIMIT não aceita placeholders, mas $quantidadeAluno foi convertido para inteiro (int).
            $sql_select_alunos = "SELECT id FROM aluno_inscricao WHERE curso_1_opcao = ? AND (idExame IS NULL OR idExame = 0) ORDER BY id $criterio LIMIT ?";
            $stmt_select_alunos = mysqli_prepare($conexao, $sql_select_alunos);
            mysqli_stmt_bind_param($stmt_select_alunos, 'si', $curso, $quantidadeAluno);

            if (!mysqli_stmt_execute($stmt_select_alunos)) {
                throw new Exception("Erro ao selecionar alunos: " . mysqli_stmt_error($stmt_select_alunos));
            }

            $resultado_alunos = mysqli_stmt_get_result($stmt_select_alunos);

            // 7. Atualiza cada aluno selecionado com o ID do novo exame.
            $sql_update_aluno = "UPDATE aluno_inscricao SET idExame = ? WHERE id = ?";
            $stmt_update_aluno = mysqli_prepare($conexao, $sql_update_aluno);

            while ($aluno = mysqli_fetch_assoc($resultado_alunos)) {
                $id_aluno = $aluno['id'];
                mysqli_stmt_bind_param($stmt_update_aluno, 'ii', $idExame, $id_aluno);
                if (!mysqli_stmt_execute($stmt_update_aluno)) {
                    throw new Exception("Erro ao atualizar o aluno com ID $id_aluno: " . mysqli_stmt_error($stmt_update_aluno));
                }
            }
            mysqli_stmt_close($stmt_update_aluno);
            mysqli_stmt_close($stmt_select_alunos);

            // 8. Se todas as operações foram bem-sucedidas, comita a transação.
            mysqli_commit($conexao);
            $feedback = [
                'title' => 'Sucesso!',
                'text' => 'Exame criado e alunos associados com sucesso.',
                'icon' => 'success'
            ];
        } catch (Exception $e) {
            // 9. Se ocorreu algum erro, reverte todas as operações.
            mysqli_rollback($conexao);
            // Para depuração, você pode logar o erro: error_log($e->getMessage());
            $feedback = [
                'title' => 'Erro na Operação',
                'text' => 'Não foi possível concluir a operação no banco de dados. Tente novamente.',
                'icon' => 'error'
            ];
        }
    }
}
?>

<div class="d-flex">
    <?php include_once "menulateral.php"; ?>
    <div class="flex-grow-1">
        <?php include_once "container_pricipal.php"; ?>

        <!-- Conteúdo principal -->
        <main class="p-4" style="background-color: #f8f9fa;">
            <div class="container-fluid">

                <!--== BODY INNER CONTAINER ==-->
                <div class="sb2-2">
                    <!-- Breadcrumbs -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-white shadow-sm p-3 rounded-3 mb-4">
                            <li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home" aria-hidden="true"></i> Painel</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Adicionar Novo Exame</li>
                        </ol>
                    </nav>
                    <!--== User Details ==-->
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar Novo Exame</h4>
                        </div>
                        <div class="card-body">
                            <form method="post" action="admin-exam-add.php">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nome" class="form-label">Nome do Exame</label>
                                        <input type="text" class="form-control" id="nome" name="nome" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="curso" class="form-label">Curso</label>
                                        <select class="form-select" id="curso" name="curso" required>
                                            <option value="" selected disabled>Selecione um curso...</option>
                                            <option>Informática de Gestão</option>
                                            <option>Comércio</option>
                                            <option>Secretariado</option>
                                            <option>Finanças</option>
                                            <option>Contabilidade</option>
                                            <option>Gestão de Recursos Humanos</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="data" class="form-label">Data</label>
                                        <input type="date" class="form-control" id="data" name="data" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="horaInicio" class="form-label">Hora de Início</label>
                                        <input type="time" class="form-control" id="horaInicio" name="horaInicio" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="horaFim" class="form-label">Hora de Fim</label>
                                        <input type="time" class="form-control" id="horaFim" name="horaFim" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="sala" class="form-label">Local (Sala)</label>
                                        <input type="text" class="form-control" id="sala" name="sala" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="quantidadeAluno" class="form-label">Quantidade de Alunos</label>
                                        <select class="form-select" id="quantidadeAluno" name="quantidadeAluno" required>
                                            <option value="" disabled selected>Selecione...</option>
                                            <option value="1">1</option>
                                            <option value="3">3</option>
                                            <option value="5">5</option>
                                            <option value="45">45</option>
                                            <option value="50">50</option>
                                            <option value="75">75</option>
                                            <option value="100">100</option>
                                            <option value="150">150</option>
                                            <option value="100000">Todos</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="criterio" class="form-label">Critério de Seleção</label>
                                        <select class="form-select" id="criterio" name="criterio" required>
                                            <option value="desc" selected>Últimos inscritos</option>
                                            <option value="asc">Primeiros inscritos</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="stato" class="form-label">Status</label>
                                        <select class="form-select" id="stato" name="stato" required>
                                            <option value="" disabled selected>Selecione o status</option>
                                            <option value="Ativo">Ativo</option>
                                            <option value="Inativo">Inativo</option>
                                        </select>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex justify-content-end">
                                    <button type="submit" name="cadastrar" class="btn btn-primary btn-lg"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar Exame</button>
                                </div>
                            </form>
                        </div>
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

<?php if (isset($feedback)) : ?>
    <script>
        // Exibe o feedback da operação usando SweetAlert2
        Swal.fire({
            title: '<?php echo htmlspecialchars($feedback['title'], ENT_QUOTES, 'UTF-8'); ?>',
            text: '<?php echo htmlspecialchars($feedback['text'], ENT_QUOTES, 'UTF-8'); ?>',
            icon: '<?php echo htmlspecialchars($feedback['icon'], ENT_QUOTES, 'UTF-8'); ?>'
        });
    </script>
<?php endif; ?>

</body>

</html>
