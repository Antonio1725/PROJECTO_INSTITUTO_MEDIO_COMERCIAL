<?php

if (!isset($_SESSION["nome_completo"])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: index.php");
    exit();
}

?>

<!-- Sidebar -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-white" style="background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%); width: 280px; min-height: 100vh;">
    <a href="admin" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">Painel Administrativo</span>
    </a>
    <hr>
    <?php

$nome_completo=$_SESSION["nome_completo"];
$nivel_acesso=$_SESSION["nivel_acesso"];
$imagem=$_SESSION["imagem"];

?>
    <div class="d-flex align-items-center text-white p-2">
        <img src="<?php echo $imagem; ?>" alt="" class="rounded-circle" width="50" height="50">
        <div class="ms-3">
            <h5 class="mb-0"><?php echo $nome_completo; ?></h5>
            <small><?php echo $nivel_acesso; ?></small>
        </div>
    </div>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="admin" class="nav-link text-white <?php echo (basename($_SERVER['PHP_SELF']) == 'admin.php') ? 'active' : ''; ?>">
                <i class="fa fa-bar-chart me-2"></i>
                Painel
            </a>
        </li>
        <li>
            <a href="#user-submenu" data-bs-toggle="collapse" class="nav-link text-white">
                <i class="fa fa-user me-2"></i>
                Usuários
            </a>
            <div class="collapse" id="user-submenu">
                <ul class="nav flex-column ms-4">
                    <li><a href="admin_add_usuario" class="nav-link text-white">Adicionar</a></li>
                    <li><a href="admin_listar_usuario" class="nav-link text-white">Listar</a></li>
                </ul>
            </div>
        </li>
        <li>
            <a href="#event-submenu" data-bs-toggle="collapse" class="nav-link text-white">
                <i class="fa fa-calendar me-2"></i>
                Eventos
            </a>
            <div class="collapse" id="event-submenu">
                <ul class="nav flex-column ms-4">
                    <li><a href="admin-event-add" class="nav-link text-white">Adicionar</a></li>
                    <li><a href="admin-event-all" class="nav-link text-white">Listar</a></li>
                </ul>
            </div>
        </li>
        <li>
            <a href="#news-submenu" data-bs-toggle="collapse" class="nav-link text-white">
                <i class="fa fa-bullhorn me-2"></i>
                Notícias
            </a>
            <div class="collapse" id="news-submenu">
                <ul class="nav flex-column ms-4">
                    <li><a href="admin-noticia-add" class="nav-link text-white">Adicionar</a></li>
                    <li><a href="admin-noticia-all" class="nav-link text-white">Listar</a></li>
                </ul>
            </div>
        </li>
        <li>
            <a href="#exam-submenu" data-bs-toggle="collapse" class="nav-link text-white">
                <i class="fa fa-bullhorn me-2"></i>
                Exames
            </a>
            <div class="collapse" id="exam-submenu">
                <ul class="nav flex-column ms-4">
                    <li><a href="admin-exam-add" class="nav-link text-white">Adicionar</a></li>
                    <li><a href="admin-exam-all" class="nav-link text-white">Listar</a></li>
                </ul>
            </div>
        </li>
        <li>
            <a href="#student-submenu" data-bs-toggle="collapse" class="nav-link text-white">
                <i class="fa fa-users me-2"></i>
                Estudantes
            </a>
            <div class="collapse" id="student-submenu">
                <ul class="nav flex-column ms-4">
                    <li><a href="listagem_alunos" class="nav-link text-white">Alunos Inscritos</a></li>
                    <li><a href="matricula" class="nav-link text-white">Alunos Matriculados</a></li>
                </ul>
            </div>
        </li>
        <li>
            <a href="comentarios" class="nav-link text-white">
                <i class="fa fa-comments me-2"></i>
                Comentários
            </a>
        </li>
        <li>
            <a href="#settings-submenu" data-bs-toggle="collapse" class="nav-link text-white">
                <i class="fa fa-cog me-2"></i>
                Configurações
            </a>
            <div class="collapse" id="settings-submenu">
                <ul class="nav flex-column ms-4">
                    <li><a href="ativarCriterio" class="nav-link text-white">Ativar Critério de Aprovação</a></li>
                    <li><a href="configurarVagas" class="nav-link text-white">Vagas</a></li>
                    <li><a href="ativarDestivarConsultaResultado" class="nav-link text-white">Ativar/Destivar Nota/Exame/Inscrição</a></li>
                </ul>
            </div>
        </li>
    </ul>
    <hr>
    <!-- Pode adicionar um dropdown para logout aqui se quiser -->
</div>