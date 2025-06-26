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


// --- DADOS PARA A PÁGINA ---
// Inclui o cabeçalho da página.
include_once "header.php";

// Busca os dados dos usuários.
$usuarios = new Usuarios($conexao);
$lista_usuarios = $usuarios->read();

// Processa mensagens flash da sessão (para feedback após ações como deletar).
$flash_message = null;
if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
} 
// Fallback para o método antigo via GET (pode ser removido no futuro).
else {
    $status = isset($_GET['status']) ? $_GET['status'] : null;
    if ($status === 'success') {
        $flash_message = ['type' => 'success', 'text' => 'Usuário excluído com sucesso!'];
    } elseif ($status === 'error') {
        $flash_message = ['type' => 'error', 'text' => 'Ocorreu um erro ao excluir o usuário.'];
    } elseif ($status === 'invalid') {
        $flash_message = ['type' => 'warning', 'text' => 'ID do usuário inválido.'];
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
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home" aria-hidden="true"></i> Painel</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Usuários</li>
                    </ol>
                </nav>

                <!-- Card de Lista de Usuários -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fa fa-users" aria-hidden="true"></i> Lista de Usuários</h4>
                        <a href="admin_add_usuario.php" class="btn btn-light">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar Novo
                        </a>
                    </div>
                    <div class="card-body">
                        
                        <!-- Exibe a mensagem de status -->
                        <?php if ($flash_message): ?>
                            <div class="alert alert-<?php echo $flash_message['type'] === 'error' ? 'danger' : ($flash_message['type'] === 'warning' ? 'warning' : 'success'); ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($flash_message['text']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Nome Completo</th>
                                        <th>Nome Usual</th>
                                        <th>E-mail</th>
                                        <th>Status</th>
                                        <th>Nível de Acesso</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($lista_usuarios)) : ?>
                                        <?php foreach ($lista_usuarios as $usuario) : ?>
                                            <tr>
                                                <td>
                                                    <img src="<?php echo htmlspecialchars($usuario['imagem']); ?>" alt="Imagem de <?php echo htmlspecialchars($usuario['nome_usual']); ?>" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                                </td>
                                                <td><?php echo htmlspecialchars($usuario['nome_completo']); ?></td>
                                                <td><?php echo htmlspecialchars($usuario['nome_usual']); ?></td>
                                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = $usuario['estato'] === 'Ativo' ? 'bg-success' : 'bg-secondary';
                                                    echo "<span class=\"badge {$status_class}\">" . htmlspecialchars($usuario['estato']) . "</span>";
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $nivel = htmlspecialchars($usuario['nivel_acesso']);
                                                    $nivel_class = 'bg-secondary'; // Default
                                                    if ($nivel === 'admin') $nivel_class = 'bg-primary';
                                                    if ($nivel === 'editor') $nivel_class = 'bg-info text-dark';
                                                    echo "<span class=\"badge {$nivel_class}\">" . ucfirst($nivel) . "</span>";
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="editar_usuario?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger" title="Deletar" onclick="deleteUser(<?php echo $usuario['id']; ?>)">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="7" class="text-center p-4">Nenhum usuário encontrado.</td>
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
<!-- Custom JS -->
<script src="js/custom.js"></script>
<!-- SweetAlert2 para confirmação de exclusão -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    function deleteUser(usuarioId) {
        Swal.fire({
            title: 'Tem certeza que deseja excluir este usuário?',
            text: "Essa ação não poderá ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redireciona para o script de exclusão
                window.location.href = 'models/classAux/excluir_usuario.php?id=' + usuarioId;
            }
        });
    }
</script>

</body>
</html>