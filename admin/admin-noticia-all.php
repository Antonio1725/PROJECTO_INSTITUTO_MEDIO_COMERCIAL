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
include_once "models/Noticias.php";

// Lógica para processar a ação de apagar
if (isset($_GET['acao']) && $_GET['acao'] == 'apagar' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 1. Buscar o caminho da imagem para excluí-la do servidor
    $stmt_img = $conexao->prepare("SELECT url_imagem FROM noticias WHERE id = ?");
    $stmt_img->bind_param("i", $id);
    $stmt_img->execute();
    $result_img = $stmt_img->get_result();
    if ($noticia_img = $result_img->fetch_assoc()) {
        if (!empty($noticia_img['url_imagem']) && file_exists($noticia_img['url_imagem'])) {
            unlink($noticia_img['url_imagem']); // Apaga o arquivo da imagem
        }
    }
    $stmt_img->close();

    // 2. Apagar o registro do banco de dados
    $stmt_delete = $conexao->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        $_SESSION['feedback'] = ['tipo' => 'success', 'mensagem' => 'Notícia apagada com sucesso!'];
    } else {
        $_SESSION['feedback'] = ['tipo' => 'danger', 'mensagem' => 'Erro ao apagar a notícia.'];
    }
    $stmt_delete->close();

    // Redireciona para a mesma página para limpar a URL (Padrão PRG)
    header("Location: admin-noticia-all.php");
    exit();
}

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
                        <li class="breadcrumb-item active" aria-current="page">Lista de Notícias</li>
                    </ol>
                </nav>

                <?php
                // Exibe a mensagem de feedback, se houver.
                if (isset($_SESSION['feedback'])) {
                    echo '<div class="alert alert-' . htmlspecialchars($_SESSION['feedback']['tipo']) . ' alert-dismissible fade show" role="alert">';
                    echo htmlspecialchars($_SESSION['feedback']['mensagem']);
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                    unset($_SESSION['feedback']); // Limpa a mensagem da sessão
                }
                ?>

                <!-- Tabela de Notícias -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fa fa-newspaper-o me-2" aria-hidden="true"></i> Todas as Notícias</h4>
                        <a href="admin-noticia-add.php" class="btn btn-primary">
                            <i class="fa fa-plus-circle me-2" aria-hidden="true"></i> Adicionar Nova
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Imagem</th>
                                        <th scope="col">Título</th>
                                        <th scope="col">Conteúdo</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Publicação</th>
                                        <th scope="col" style="width: 170px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Busca os dados da tabela noticias, selecionando colunas específicas
                                    $sql = "SELECT id, titulo, conteudo, url_imagem, stato, data_publicacao FROM noticias ORDER BY id DESC";
                                    $result = $conexao->query($sql);
                                    ?>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($noticia = $result->fetch_assoc()): ?>
                                            <tr>
                                                <th scope="row"><?= $noticia['id'] ?></th>
                                                <td>
                                                    <?php if (!empty($noticia['url_imagem']) && file_exists($noticia['url_imagem'])): ?>
                                                        <img src="<?= htmlspecialchars($noticia['url_imagem']) ?>" alt="Imagem da notícia" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <span class="text-muted">Sem imagem</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($noticia['titulo']) ?></td>
                                                <td>
                                                    <?php
                                                    // Limita o conteúdo para melhor exibição na tabela
                                                    $conteudo_curto = mb_substr(strip_tags($noticia['conteudo']), 0, 100);
                                                    echo htmlspecialchars($conteudo_curto) . (mb_strlen($noticia['conteudo']) > 100 ? '...' : '');
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status_class = $noticia['stato'] == 'Ativo' ? 'badge bg-success' : 'badge bg-secondary';
                                                    echo '<span class="' . $status_class . '">' . htmlspecialchars($noticia['stato']) . '</span>';
                                                    ?>
                                                </td>
                                                <td><?= date("d/m/Y H:i", strtotime($noticia['data_evento'])) ?></td>
                                                <td>
                                                    <a href="admin-noticia-edit.php?id=<?= $noticia['id'] ?>" class="btn btn-warning btn-sm text-white" title="Editar">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" title="Deletar" onclick="confirmDelete(<?= $noticia['id'] ?>)">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Nenhuma notícia cadastrada.</td>
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

<!-- SweetAlert para confirmação de exclusão -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    function confirmDelete(noticiaId) {
        Swal.fire({
            title: 'Tem certeza que deseja excluir esta notícia?',
            text: "Esta ação não poderá ser desfeita e a imagem associada também será removida!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redireciona para a URL com a ação de apagar
                window.location.href = 'admin-noticia-all.php?acao=apagar&id=' + noticiaId;
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
