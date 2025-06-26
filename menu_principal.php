

<!-- Navbar Start -->
<style>
    .navbar-custom {
        box-shadow: 0 4px 24px rgba(30,64,175,0.10), 0 1.5px 4px rgba(0,0,0,0.04);
        border-bottom: 3px solid #2563eb;
        background: linear-gradient(90deg, #fff 80%, #e0e7ff 100%);
    }
    .navbar-brand h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e40af;
        letter-spacing: 1px;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    .navbar-brand img {
        width: 60px;
        height: 40px;
        margin-right: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(30,64,175,0.10);
        background: #f1f5f9;
        object-fit: contain;
    }
    .navbar-nav .nav-link {
        font-weight: 600;
        color: #1e293b !important;
        padding: 0.7rem 1.2rem;
        border-radius: 8px;
        margin: 0 0.2rem;
        transition: background 0.18s, color 0.18s;
        font-size: 1.08rem;
        letter-spacing: 0.5px;
    }
    .navbar-nav .nav-link.active, .navbar-nav .nav-link:hover {
        background: linear-gradient(90deg, #2563eb 60%, #1e40af 100%);
        color: #fff !important;
        box-shadow: 0 2px 8px rgba(30,64,175,0.08);
    }
    .navbar-toggler {
        border: none;
        outline: none;
        box-shadow: none;
    }
    .btn-navbar-action {
        background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
        color: #fff !important;
        font-weight: 700;
        border-radius: 10px;
        padding: 0.7rem 2.2rem;
        font-size: 1.08rem;
        box-shadow: 0 2px 8px rgba(30,64,175,0.10);
        margin-left: 1.2rem;
        transition: background 0.18s, box-shadow 0.18s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.7rem;
    }
    .btn-navbar-action.active, .btn-navbar-action:hover {
        background: linear-gradient(90deg, #0ea5e9 60%, #1e40af 100%);
        color: #fff !important;
        box-shadow: 0 4px 16px rgba(30,64,175,0.16);
    }
    @media (max-width: 991.98px) {
        .navbar-nav .nav-link {
            margin-bottom: 0.5rem;
            border-radius: 6px;
        }
        .btn-navbar-action {
            width: 100%;
            margin: 1rem 0 0 0;
            justify-content: center;
        }
    }
</style>
<nav class="navbar navbar-expand-lg navbar-custom navbar-light sticky-top p-0 wow fadeIn" data-wow-delay="0.1s">
    <a href="index" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <img alt="Logotipo" src="img/logotipo.png"/>
        <h1 class="m-0 text-primary1">Instituto Médio Comercial de Luanda</h1>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-label="Abrir menu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0 align-items-lg-center">
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            ?>
            <a href="index" class="nav-item nav-link <?php if($current_page == 'index.php') echo 'active'; ?>"><i class="fa fa-home me-1"></i>Home</a>
            <a href="evento" class="nav-item nav-link <?php if($current_page == 'evento.php') echo 'active'; ?>"><i class="fa fa-calendar me-1"></i>Eventos</a>
            <a href="noticia" class="nav-item nav-link <?php if($current_page == 'noticia.php') echo 'active'; ?>"><i class="fa fa-newspaper-o me-1"></i>Notícias</a>
            <a href="about" class="nav-item nav-link <?php if($current_page == 'about.php') echo 'active'; ?>"><i class="fa fa-info-circle me-1"></i>Sobre</a>
            <a href="contact" class="nav-item nav-link <?php if($current_page == 'contact.php') echo 'active'; ?>"><i class="fa fa-envelope me-1"></i>Contactos</a>
            <?php
                include_once "admin/models/conexao.php";
                $sql = "SELECT * FROM controlo_links WHERE id= '1'";
                $query = mysqli_query($conexao, $sql);
                $resultado = mysqli_fetch_assoc($query);

                // Exibe apenas um link por vez, na ordem: Exame > Inscrição > Nota
                if ($resultado['controlExame'] == 'S') {
            ?>
                <a href="consultarExame" class="btn-navbar-action d-none d-lg-inline-flex <?php if($current_page == 'consultarExame.php') echo 'active'; ?>">
                    <i class="fa fa-search"></i> Consultar Data de Exame
                </a>
            <?php
                } elseif ($resultado['controlInscricao'] == 'S') {
            ?>
                <a href="inscricao" class="btn-navbar-action d-none d-lg-inline-flex <?php if($current_page == 'inscricao.php') echo 'active'; ?>">
                    <i class="fa fa-pencil-square-o"></i> Inscrever-se
                </a>
            <?php
                } elseif ($resultado['controlNota'] == 'S') {
            ?>
                <a href="consultarResultadoNota" class="btn-navbar-action d-none d-lg-inline-flex <?php if($current_page == 'consultarResultadoNota.php') echo 'active'; ?>">
                    <i class="fa fa-check-circle"></i> Consultar Resultado de Exame
                </a>
            <?php
                }
            ?>
        </div>
        <!-- Botão de ação visível no mobile -->
        <div class="d-lg-none px-4 pb-3">
            <?php
                if ($resultado['controlExame'] == 'S') {
            ?>
                <a href="consultarExame" class="btn-navbar-action w-100 <?php if($current_page == 'consultarExame.php') echo 'active'; ?>">
                    <i class="fa fa-search"></i> Consultar Data de Exame
                </a>
            <?php
                } elseif ($resultado['controlInscricao'] == 'S') {
            ?>
                <a href="inscricao" class="btn-navbar-action w-100 <?php if($current_page == 'inscricao.php') echo 'active'; ?>">
                    <i class="fa fa-pencil-square-o"></i> Inscrever-se
                </a>
            <?php
                } elseif ($resultado['controlNota'] == 'S') {
            ?>
                <a href="consultarResultadoNota" class="btn-navbar-action w-100 <?php if($current_page == 'consultarResultadoNota.php') echo 'active'; ?>">
                    <i class="fa fa-check-circle"></i> Consultar Resultado de Exame
                </a>
            <?php
                }
            ?>
        </div>
    </div>
</nav>
<!-- Navbar End -->

