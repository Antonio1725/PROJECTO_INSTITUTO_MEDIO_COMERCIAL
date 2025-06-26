<?php
// Conexão com o banco de dados
require_once '../Usuarios.php';
require_once '../Conexao.php';

// Verifica se o ID foi passado via GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $usuario_id = (int) $_GET['id'];

    // Cria a instância da classe Usuarios
    $usuarios = new Usuarios($conexao);

    // Deleta o usuário do banco de dados
    if ($usuarios->delete($usuario_id)) {
        // Redireciona para a lista de usuários com uma mensagem de sucesso
        header("Location: ../../admin_listar_usuario.php?status=success");
        exit;
    } else {
        // Redireciona em caso de erro
        header("Location: ../../admin_listar_usuario.php?status=error");
        exit;
    }
} else {
    // Redireciona caso o ID não seja válido
    header("Location: ../../admin_listar_usuario.php?status=invalid");
    exit;
}
?>
