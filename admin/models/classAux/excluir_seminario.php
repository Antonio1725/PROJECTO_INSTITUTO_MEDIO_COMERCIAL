<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// Incluindo a classe de conexão e operações de seminário
require_once '../Seminario.php';
require_once '../Conexao.php';




// Instanciando a classe Seminario
$seminario = new Seminario($conexao);

// Verificando se o ID foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Deletando o seminário
    $resultado = $seminario->delete($id);


    // Deleta o usuário do banco de dados
    if ($resultado) {
        // Redireciona para a lista de usuários com uma mensagem de sucesso
        header("Location: ../../admin-seminar-all.php?status=success");
        exit;
    } else {
        // Redireciona em caso de erro
        header("Location: ../../admin-seminar-all.php?status=error");
        exit;
    }
} else {
    // Redireciona caso o ID não seja válido
    header("Location: ../../admin-seminar-all.php?status=invalid");
    exit;
}
?>
