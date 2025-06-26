<?php
// Conexão com o banco de dados
require_once '../Cursos.php';
require_once '../Conexao.php';

// Verifica se o ID foi passado via GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $curso_id = (int) $_GET['id'];

    // Cria a instância da classe Usuarios
    $cursos = new Cursos($conexao);

    // Deleta o usuário do banco de dados
    if ($cursos->delete($curso_id)) {
        // Redireciona para a lista de usuários com uma mensagem de sucesso
        header("Location: ../../adminListarCursos.php?status=success");
        exit;
    } else {
        // Redireciona em caso de erro
        header("Location: ../../adminListarCursos.php?status=error");
        exit;
    }
} else {
    // Redireciona caso o ID não seja válido
    header("Location: ../../adminListarCursos.php?status=invalid");
    exit;
}
?>
