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

// Define os campos dos cursos para facilitar a manutenção e evitar repetição
$cursos_vagas_fields = [
    'informatica' => 'Informática de Gestão',
    'comercio' => 'Comércio',
    'secretariado' => 'Secretariado',
    'financas' => 'Finanças',
    'contabilidade' => 'Contabilidade',
    'gestao_recursos_humanos' => 'Gestão de Recursos Humanos'
];

// Lógica de processamento do formulário (movida para o topo para seguir o padrão PRG)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salvar'])) {
    try {
        // Prepara os dados para a inserção/atualização
        $vagas_data = [];
        foreach (array_keys($cursos_vagas_fields) as $field) {
            // Garante que o valor seja um inteiro não negativo, padrão 0.
            $vagas_data[$field] = max(0, filter_input(INPUT_POST, $field, FILTER_VALIDATE_INT, ['options' => ['default' => 0]]));
        }

        // Utiliza INSERT ... ON DUPLICATE KEY UPDATE para uma operação atômica e limpa.
        // Isso pressupõe que 'id' é uma CHAVE PRIMÁRIA ou ÚNICA.
        $sql = "INSERT INTO controlovagas (id, informatica, comercio, secretariado, financas, contabilidade, gestao_recursos_humanos) 
                VALUES (1, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                informatica = VALUES(informatica), 
                comercio = VALUES(comercio), 
                secretariado = VALUES(secretariado), 
                financas = VALUES(financas), 
                contabilidade = VALUES(contabilidade), 
                gestao_recursos_humanos = VALUES(gestao_recursos_humanos)";

        $stmt = $conexao->prepare($sql);
        if (!$stmt) {
            throw new Exception("Falha ao preparar a consulta: " . $conexao->error);
        }

        // 'i' para cada campo de inteiro
        $stmt->bind_param(
            "iiiiii",
            $vagas_data['informatica'],
            $vagas_data['comercio'],
            $vagas_data['secretariado'],
            $vagas_data['financas'],
            $vagas_data['contabilidade'],
            $vagas_data['gestao_recursos_humanos']
        );

        if ($stmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Vagas configuradas com sucesso!'];
        } else {
            throw new Exception("Falha ao executar a consulta: " . $stmt->error);
        }
        $stmt->close();

    } catch (Exception $e) {
        // Em caso de erro, define uma mensagem de falha.
        // Para depuração: error_log($e->getMessage());
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Ocorreu um erro ao configurar as vagas. Nenhuma alteração foi feita.'];
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

// Busca os dados existentes para preencher o formulário
$sql_vagas = "SELECT * FROM controlovagas WHERE id = 1";
$resultado_vagas = $conexao->query($sql_vagas);
$dados_vagas = $resultado_vagas && $resultado_vagas->num_rows > 0 
    ? $resultado_vagas->fetch_assoc() 
    : [];

// Inicializa as variáveis de vagas com os dados do banco ou com 0 como padrão
$vagas = [];
foreach (array_keys($cursos_vagas_fields) as $field) {
    $vagas[$field] = (int)($dados_vagas[$field] ?? 0);
}
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
                        <li class="breadcrumb-item active" aria-current="page">Configurar Vagas</li>
                    </ol>
                </nav>

                <!-- Card para configuração de vagas -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0 fw-bold text-primary"><i class="fa fa-cogs me-2" aria-hidden="true"></i>Configurar Vagas por Curso</h4>
                    </div>
                    <div class="card-body">
                        <!-- Exibição de mensagens de feedback -->
                        <?php if ($message): ?>
                        <div class="alert alert-<?= htmlspecialchars($message['type']) ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message['text']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

                        <p>Defina o número de vagas disponíveis para cada curso. Estes valores serão utilizados no processo de seleção automática de candidatos.</p>

                        <form method="post" class="mt-4">
                            <div class="row">
                                <?php foreach ($cursos_vagas_fields as $field_name => $label): ?>
                                <div class="col-md-6 mb-3">
                                    <label for="<?= $field_name ?>" class="form-label fw-bold"><?= htmlspecialchars($label) ?></label>
                                    <input type="number" id="<?= $field_name ?>" name="<?= $field_name ?>" class="form-control" value="<?= htmlspecialchars($vagas[$field_name]) ?>" min="0" required>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-3">
                                <button type="submit" name="salvar" class="btn btn-primary btn-lg">
                                    <i class="fa fa-save me-2"></i> Salvar Configurações
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
