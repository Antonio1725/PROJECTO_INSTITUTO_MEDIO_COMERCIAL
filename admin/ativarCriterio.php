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

// Lógica de processamento do formulário (movida para o topo)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ativar'])) {
    try {
        // Iniciar transação para garantir a integridade dos dados
        $conexao->begin_transaction();

        // 1. Buscar o número de vagas para cada curso
        $sql_vagas = "SELECT informatica, comercio, secretariado, financas, contabilidade, gestao_recursos_humanos FROM controlovagas WHERE id = 1";
        $resultado_vagas = $conexao->query($sql_vagas);
        $vagas = $resultado_vagas ? $resultado_vagas->fetch_assoc() : null;

        if (!$vagas) {
            throw new Exception("Não foi possível encontrar as configurações de vagas.");
        }

        // Mapeia os nomes dos cursos na tabela 'aluno_inscricao' para as colunas na tabela 'controlovagas'
        $cursos_e_vagas = [
            'Informática de Gestão' => (int)($vagas['informatica'] ?? 0),
            'Secretariado' => (int)($vagas['secretariado'] ?? 0),
            'Finanças' => (int)($vagas['financas'] ?? 0),
            'Contabilidade' => (int)($vagas['contabilidade'] ?? 0),
            'Gestão de Recursos Humanos' => (int)($vagas['gestao_recursos_humanos'] ?? 0),
            // Adicione outros cursos e suas respectivas colunas de vagas aqui
        ];

        // 2. Primeiro, redefine todos os alunos para 'Não Apto'. Isso simplifica a lógica.
        $conexao->query("UPDATE aluno_inscricao SET obs = 'Não Apto'");

        $ids_aptos = [];

        // 3. Para cada curso, seleciona os melhores candidatos até o limite de vagas
        foreach ($cursos_e_vagas as $curso_nome => $limite_vagas) {
            if ($limite_vagas > 0) {
                $stmt = $conexao->prepare(
                    "SELECT id FROM aluno_inscricao 
                     WHERE curso_1_opcao = ? 
                     ORDER BY nota DESC, data_nascimento ASC, data_inscricao ASC 
                     LIMIT ?"
                );
                $stmt->bind_param("si", $curso_nome, $limite_vagas);
                $stmt->execute();
                $resultado = $stmt->get_result();
                while ($aluno = $resultado->fetch_assoc()) {
                    $ids_aptos[] = $aluno['id'];
                }
                $stmt->close();
            }
        }

        // 4. Atualiza todos os alunos selecionados para 'Apto' em uma única consulta
        if (!empty($ids_aptos)) {
            $placeholders = implode(',', array_fill(0, count($ids_aptos), '?'));
            $types = str_repeat('i', count($ids_aptos));
            
            $stmt_update = $conexao->prepare("UPDATE aluno_inscricao SET obs = 'Apto' WHERE id IN ($placeholders)");
            $stmt_update->bind_param($types, ...$ids_aptos);
            $stmt_update->execute();
            $stmt_update->close();
        }

        // 5. Confirma as alterações no banco de dados
        $conexao->commit();
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Processo de seleção concluído com sucesso!'];

    } catch (Exception $e) {
        // Em caso de erro, reverte todas as alterações
        $conexao->rollback();
        // Para depuração, você pode querer logar o erro: error_log($e->getMessage());
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Ocorreu um erro ao processar os dados. Nenhuma alteração foi feita.'];
    }

    // Redireciona para a mesma página para evitar reenvio do formulário (Padrão PRG)
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
                        <li class="breadcrumb-item active" aria-current="page">Ativar Critério de Seleção</li>
                    </ol>
                </nav>

                <!-- Exibição de mensagens de feedback -->
                <?php if ($message): ?>
                <div class="alert alert-<?= htmlspecialchars($message['type']) ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message['text']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- Card para ativação do critério -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0 fw-bold text-primary"><i class="fa fa-check-circle me-2" aria-hidden="true"></i>Ativar Critério de Seleção</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Este processo irá selecionar os alunos mais bem classificados para cada curso com base na nota, data de nascimento e data de inscrição, de acordo com o número de vagas disponíveis.</p>
                        <p class="card-text">Os alunos selecionados serão marcados como "Apto" e os restantes como "Não Apto".</p>
                        <div class="alert alert-warning" role="alert">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <strong>Atenção:</strong> Esta ação é crítica e irá sobrescrever o estado de aptidão de todos os alunos inscritos.
                        </div>
                        <form method="post" onsubmit="return confirm('Tem a certeza que deseja executar este processo? Esta ação não pode ser desfeita facilmente.');">
                            <button type="submit" name="ativar" class="btn btn-primary btn-lg mt-3">
                                <i class="fa fa-play-circle me-2" aria-hidden="true"></i> Iniciar Processo de Seleção
                            </button>
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
