<?php
// Conexão com o banco de dados
require_once '../Carrossel.php';
require_once '../Conexao.php';

// Verifica se o ID foi passado via GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $slider_id = (int) $_GET['id'];

    // Cria a instância da classe Usuarios
    $carrossel = new Carrossel($conexao);

    // Deleta o usuário do banco de dados
    if ($carrossel->delete($slider_id)) {
        // Redireciona para a lista de usuários com uma mensagem de sucesso
        header("Location: ../../admin-slider.php?status=success");
        exit;
    } else {
        // Redireciona em caso de erro
        header("Location: ../../admin-slider.php?status=error");
        exit;
    }
} else {
    // Redireciona caso o ID não seja válido
    header("Location: ../../admin-slider.php?status=invalid");
    exit;
}
?>
