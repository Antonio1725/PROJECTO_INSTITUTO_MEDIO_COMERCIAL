  
<?php
session_start();
$nome_completo = $_SESSION["nome_completo"];
$nivel_acesso = $_SESSION["nivel_acesso"];
$imagem = $_SESSION["imagem"];
?>
  <!--== CONTAINER PRINCIPAL ==-->
<nav class="navbar navbar-light bg-white shadow-sm px-3">
    <div class="container-fluid">
        <!-- Lado Esquerdo: Toggle do Menu e Título -->
        <div class="d-flex align-items-center">
            <!-- Botão para alternar a barra lateral em telas menores -->
            <a href="#" class="atab-menu me-3 text-dark fs-4 d-lg-none"><i class="fa fa-bars" aria-hidden="true"></i></a>
            
            <!-- Título do Painel -->
            <a class="navbar-brand fw-bold d-none d-md-block" href="admin" style="color: #1e40af;">
            Instituto Médio Comercial de Luanda
            </a>
        </div>

        <!-- Lado Direito: Notificações e Perfil do Usuário -->
        <div class="d-flex align-items-center">
            
            <!-- Notificações -->
            <div class="me-3">
                <a class='btn btn-light rounded-circle position-relative' href="comentarios" title="Novas mensagens">
                    <i class="fa fa-commenting-o fs-5" aria-hidden="true"></i>
                   
                </a>
            </div>

            <!-- Dropdown do Usuário -->
            <div class="dropdown">
                <a href='#' class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo $imagem; ?>" alt="user" width="36" height="36" class="rounded-circle me-2" style="object-fit: cover;">
                    <span class="d-none d-sm-inline mx-1 fw-bold"><?php echo explode(" ", $nome_completo)[0]; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser" style="min-width: 240px;">
                    <li>
                        <div class="px-3 py-2 text-center">
                            <img src="<?php echo $imagem; ?>" alt="user" width="64" height="64" class="rounded-circle mb-2" style="object-fit: cover;">
                            <h6 class="mb-0"><?php echo $nome_completo; ?></h6>
                            <small class="text-muted"><?php echo $nivel_acesso; ?></small>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="post" class="px-2">
                            <button name="nSair" class="btn btn-danger w-100" type="submit"><i class="fa fa-sign-out fa-fw me-2" aria-hidden="true"></i>Sair</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<?php
if(isset($_POST["nSair"])) {
    session_destroy();
    echo "<script>window.location='index'</script>";
}
?>
