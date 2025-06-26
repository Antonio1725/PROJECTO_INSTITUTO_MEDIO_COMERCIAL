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

require_once "models/conexao.php";

// Lógica de processamento do formulário (Padrão PRG)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salvar'])) {
    try {
        $escolha = $_POST['escolha'] ?? '';
        
        // Valida a escolha para garantir que é uma das colunas esperadas
        $opcoes_validas = ['controlNota', 'controlExame', 'controlInscricao'];
        if (!in_array($escolha, $opcoes_validas)) {
            throw new Exception("Opção inválida selecionada.");
        }

        // Define qual controle estará ativo ('S') e os outros inativos ('N')
        $controlNota = ($escolha == 'controlNota') ? 'S' : 'N';
        $controlExame = ($escolha == 'controlExame') ? 'S' : 'N';
        $controlInscricao = ($escolha == 'controlInscricao') ? 'S' : 'N';

        // Usa prepared statements para segurança e robustez
        $sql = "UPDATE controlo_links SET controlNota = ?, controlExame = ?, controlInscricao = ? WHERE id = 1";
        $stmt = $conexao->prepare($sql);
        if (!$stmt) {
            throw new Exception("Falha ao preparar a consulta: " . $conexao->error);
        }

        $stmt->bind_param("sss", $controlNota, $controlExame, $controlInscricao);

        if ($stmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Status do portal atualizado com sucesso!'];
        } else {
            throw new Exception("Falha ao executar a consulta: " . $stmt->error);
        }
        $stmt->close();

    } catch (Exception $e) {
        // Em caso de erro, define uma mensagem de falha.
        // Para depuração: error_log($e->getMessage());
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Ocorreu um erro ao atualizar o status. Nenhuma alteração foi feita.'];
    }

    // Redireciona para a mesma página para evitar reenvio do formulário
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Pega a mensagem da sessão (se houver) para exibir ao usuário
$message = null;
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Inclui o cabeçalho da página
include_once "header.php";

// Otimização para buscar todas as contagens em uma única e robusta consulta
$sql_contagens = "
    SELECT 
        (SELECT COUNT(id) FROM matricula) as total_matricula,
        (SELECT COUNT(id) FROM aluno_inscricao) as total_inscricao,
        (SELECT COUNT(id) FROM noticias WHERE stato='Ativo') as total_noticias,
        (SELECT COUNT(id) FROM eventos WHERE stato='Ativo') as total_eventos,
        (SELECT COUNT(id) FROM cursos) as total_cursos
";
$resultado_contagens = $conexao->query($sql_contagens);
$contagens = $resultado_contagens ? $resultado_contagens->fetch_assoc() : [];

// Atribui os valores usando o operador de coalescência nula para definir 0 em caso de falha.
$numLinhaMatricula = $contagens['total_matricula'] ?? 0;
$numLinhaAEstudante = $contagens['total_inscricao'] ?? 0;
$numLinhaNoticias = $contagens['total_noticias'] ?? 0;
$numEventos = $contagens['total_eventos'] ?? 0;
$numLinhaCursos = $contagens['total_cursos'] ?? 0;

// Busca o status atual para preencher o formulário
$sql_status = "SELECT controlNota, controlExame, controlInscricao FROM controlo_links WHERE id = 1";
$resultado_status = $conexao->query($sql_status);
$controles = $resultado_status ? $resultado_status->fetch_assoc() : [
    'controlNota' => 'N', 
    'controlExame' => 'N', 
    'controlInscricao' => 'N'
];

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
                    <ol class="breadcrumb bg-white shadow-sm p-3 rounded-3">
                        <li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home" aria-hidden="true"></i> Painel</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gerenciar Status do Portal</li>
                    </ol>
                </nav>

                <!-- Card para gerenciamento de status -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0 fw-bold text-primary"><i class="fa fa-toggle-on me-2" aria-hidden="true"></i>Gerenciar Status do Portal do Candidato</h4>
                    </div>
                    <div class="card-body">
                        <!-- Exibição de mensagens de feedback -->
                        <?php if ($message): ?>
                        <div class="alert alert-<?= htmlspecialchars($message['type']) ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message['text']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

                        <p>Selecione qual funcionalidade estará ativa para os candidatos no portal. Apenas uma opção pode estar ativa por vez.</p>

                        <form method="post" class="mt-4">
                            <div class="mb-3">
                                <label for="status_escolha" class="form-label fw-bold">Funcionalidade Ativa:</label>
                                <select class="form-select" id="status_escolha" name="escolha" required>
                                    <option value="controlInscricao" <?= $controles['controlInscricao'] == 'S' ? 'selected' : '' ?>>Permitir Inscrição</option>
                                    <option value="controlExame" <?= $controles['controlExame'] == 'S' ? 'selected' : '' ?>>Consulta de Data dos Exames</option>
                                    <option value="controlNota" <?= $controles['controlNota'] == 'S' ? 'selected' : '' ?>>Consulta de Resultado (Nota)</option>
                                </select>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" name="salvar" class="btn btn-primary btn-lg">
                                    <i class="fa fa-save me-2"></i> Salvar Alteração
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

</body>
</html>
