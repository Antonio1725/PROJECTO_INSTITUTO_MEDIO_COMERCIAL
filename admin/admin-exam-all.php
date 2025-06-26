<?php
// Garante que a sessão seja iniciada no início do script.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
if (!isset($_SESSION["nome_completo"])) {
    header("Location: login.php");
    exit(); // Garante que o script pare de ser executado.
}

// Inclui os arquivos necessários.
include_once "models/conexao.php";
include_once "models/Exames.php";
include_once "header.php";

$feedback = null;
$exames_model = new Exames($conexao);

// Processa a ação de apagar usando o padrão Post-Redirect-Get para evitar re-exclusão.
if (isset($_GET['acao']) && $_GET['acao'] === 'apagar' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($exames_model->delete($id)) {
        // Armazena uma mensagem de sucesso na sessão para exibição após o redirecionamento.
        $_SESSION['feedback'] = ['tipo' => 'success', 'mensagem' => 'Exame apagado com sucesso!'];
    } else {
        // Armazena uma mensagem de erro.
        $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Erro ao apagar o exame.'];
    }
    // Redireciona para a mesma página sem os parâmetros GET para limpar a URL.
    header("Location: admin-exam-all.php");
    exit();
}

// Verifica se há uma mensagem de feedback na sessão (após um redirecionamento).
if (isset($_SESSION['feedback'])) {
    $feedback = $_SESSION['feedback'];
    unset($_SESSION['feedback']); // Limpa a mensagem da sessão para não exibi-la novamente.
}

// Obtém todos os exames para exibição.
$lista_exames = $exames_model->read();

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
                        <li class="breadcrumb-item active" aria-current="page">Lista de Exames</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fa fa-list" aria-hidden="true"></i> Lista de Exames</h4>
                        <a href="admin-exam-add.php" class="btn btn-light"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar Novo Exame</a>
                    </div>
                    <div class="card-body">
                        <?php if ($feedback) : ?>
                            <div class="alert alert-<?php echo htmlspecialchars($feedback['tipo']); ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($feedback['mensagem']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nome do Exame</th>
                                        <th>Data</th>
                                        <th>Início</th>
                                        <th>Fim</th>
                                        <th>Status</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($lista_exames)) : ?>
                                        <?php foreach ($lista_exames as $exame) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($exame['id']); ?></td>
                                                <td><?php echo htmlspecialchars($exame['nome']); ?></td>
                                                <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($exame['data']))); ?></td>
                                                <td><?php echo htmlspecialchars($exame['dataInicio']); ?></td>
                                                <td><?php echo htmlspecialchars($exame['dataFim']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $exame['stato'] === 'Ativo' ? 'success' : 'secondary'; ?>">
                                                        <?php echo htmlspecialchars($exame['stato']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="editarExame.php?id=<?php echo $exame['id']; ?>" class="btn btn-warning btn-sm text-white">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $exame['id']; ?>)">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="7" class="text-center p-4">Nenhum exame encontrado.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Script para confirmação de exclusão -->
<script>
    function confirmDelete(examId) {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você realmente deseja excluir este exame? Esta ação não pode ser desfeita.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redireciona para a URL de exclusão
                window.location.href = 'admin-exam-all.php?acao=apagar&id=' + examId;
            }
        });
    }
</script>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Custom JS -->
<script src="js/custom.js"></script>

</body>
</html>
