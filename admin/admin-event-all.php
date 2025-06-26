<?php
// Garante que a sessão seja iniciada no início do script.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado, redirecionando para o login caso não esteja.
if (!isset($_SESSION["nome_completo"])) {
    header("Location: login.php");
    exit();
}

// Inclui os arquivos necessários.
include_once "models/conexao.php";
include_once "models/Eventos.php";

// Lógica para apagar um evento.
if (isset($_GET['acao']) && $_GET['acao'] === 'apagar' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Primeiro, busca o caminho da imagem para poder excluí-la do servidor.
    $stmt_select = $conexao->prepare("SELECT url_imagem FROM eventos WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();
    if ($evento = $result_select->fetch_assoc()) {
        // Se houver uma imagem e o arquivo existir, apaga o arquivo.
        if (!empty($evento['url_imagem']) && file_exists($evento['url_imagem'])) {
            unlink($evento['url_imagem']);
        }
    }
    $stmt_select->close();

    // Em seguida, apaga o registro do evento no banco de dados.
    $stmt_delete = $conexao->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt_delete->bind_param("i", $id);
    if ($stmt_delete->execute()) {
        $_SESSION['feedback'] = ['tipo' => 'success', 'mensagem' => 'Evento apagado com sucesso!'];
    } else {
        $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Erro ao apagar o evento.'];
    }
    $stmt_delete->close();

    // Redireciona para a mesma página para limpar os parâmetros GET da URL (Padrão PRG).
    header("Location: admin-event-all.php");
    exit();
}

// Busca a mensagem de feedback da sessão, se houver.
$feedback = $_SESSION['feedback'] ?? null;
unset($_SESSION['feedback']);

// Busca todos os eventos do banco de dados para listagem.
$result = $conexao->query("SELECT id, titulo, descricao, data_evento, localizacao, url_imagem, stato FROM eventos ORDER BY dataCreate DESC");

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
                        <li class="breadcrumb-item active" aria-current="page">Lista de Eventos</li>
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
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fa fa-calendar me-2" aria-hidden="true"></i> Lista de Eventos</h4>
                        <a href="admin-event-add.php" class="btn btn-light">
                            <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Adicionar Novo Evento
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Imagem</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Data do Evento</th>
                                    <th>Local</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 120px;">Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php while ($evento = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $evento['id'] ?></td>
                                            <td>
                                                <?php if (!empty($evento['url_imagem']) && file_exists($evento['url_imagem'])): ?>
                                                    <img src="<?= htmlspecialchars($evento['url_imagem']) ?>" alt="Imagem do Evento" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">Sem Imagem</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($evento['titulo']) ?></td>
                                            <td style="max-width: 250px;">
                                                <p class="text-truncate mb-0" title="<?= htmlspecialchars($evento['descricao']) ?>">
                                                    <?= htmlspecialchars($evento['descricao']) ?>
                                                </p>
                                            </td>
                                            <td><?= date("d/m/Y H:i", strtotime($evento['data_evento'])) ?></td>
                                            <td><?= htmlspecialchars($evento['local']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $evento['stato'] === 'Ativo' ? 'success' : 'secondary' ?>">
                                                    <?= htmlspecialchars($evento['stato']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="admin-event-edit.php?id=<?= $evento['id'] ?>" class="btn btn-sm btn-warning text-white" title="Editar">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-danger" title="Deletar" onclick="confirmDelete(<?= $evento['id'] ?>)">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">Nenhum evento encontrado.</td>
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

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- SweetAlert for confirmation dialog -->
<script>
    function confirmDelete(eventId) {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você realmente deseja excluir este evento? Esta ação não pode ser desfeita.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'admin-event-all.php?acao=apagar&id=' + eventId;
            }
        });
    }
</script>
<!-- Custom JS -->
<script src="js/custom.js"></script>

</body>
</html>
