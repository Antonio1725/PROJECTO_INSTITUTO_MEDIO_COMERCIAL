<?php
// Garante que a sessão seja iniciada no início do script, antes de qualquer saída HTML.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
// Esta verificação é crucial e deve ser feita antes de carregar o restante da página.
if (!isset($_SESSION["nome_completo"])) {
    // O redirecionamento via header do PHP é mais eficiente e seguro.
    header("Location: login.php");
    exit(); // Garante que o script pare de ser executado após o redirecionamento.
}

// Como a verificação de login já foi feita, podemos carregar os outros arquivos.
include_once "models/conexao.php";
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
                    <ol class="breadcrumb bg-white shadow-sm p-3 rounded-3">
                        <li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home" aria-hidden="true"></i> Painel</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Comentários</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header  bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fa fa-comments me-2" aria-hidden="true"></i> Comentários Recebidos</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            $sql_comentarios = "SELECT id, nome, email, comentario FROM comentarios ORDER BY id DESC";
                            $resultado_comentarios = $conexao->query($sql_comentarios);
                            ?>
                            <table class="table table-striped table-hover table-bordered align-middle">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 20%;">Nome</th>
                                        <th style="width: 25%;">Email</th>
                                        <th>Comentário</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($resultado_comentarios && $resultado_comentarios->num_rows > 0) {
                                        while ($linha = $resultado_comentarios->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td class="text-center"><?= htmlspecialchars($linha["id"]); ?></td>
                                                <td><?= htmlspecialchars($linha["nome"]); ?></td>
                                                <td><?= htmlspecialchars($linha["email"]); ?></td>
                                                <td><?= nl2br(htmlspecialchars($linha["comentario"])); ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                        <tr>
                                            <td colspan="4" class="text-center p-5">
                                                <i class="fa fa-info-circle fa-3x text-muted mb-3"></i>
                                                <p class="mb-0 text-muted">Nenhum comentário encontrado.</p>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
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

</body>
</html>
