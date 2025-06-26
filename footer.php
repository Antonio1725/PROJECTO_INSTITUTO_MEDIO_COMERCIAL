<!-- Footer Start -->
  
<?php
            // Processar formulário
            if (isset($_POST['nEnvair'])) {
                include_once "admin/models/conexao.php";
                
                // Verificar conexão
                if ($conexao->connect_error) {
                    die("<div class='alert alert-danger'>Falha na conexão: " . $conexao->connect_error . "</div>");
                }

                // Coletar e sanitizar dados
                $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
                $email = mysqli_real_escape_string($conexao, $_POST['email']);
                $comentario = mysqli_real_escape_string($conexao, $_POST['comentario']);

                // Query de inserção
                $sql = "INSERT INTO comentarios (nome, email, comentario, data_envio) 
                        VALUES ('$nome', '$email', '$comentario', NOW())";
                if ($conexao->query($sql) === TRUE) {
                    echo '<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
                            <div id="successToast" class="toast fade show bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                <div class="toast-body d-flex justify-content-between align-items-center">
                                    Comentário enviado com sucesso!
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                          </div>
                          <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                var toastEl = document.getElementById("successToast");
                                var toast = new bootstrap.Toast(toastEl);
                                toast.show();
                                
                                toastEl.querySelector(".btn-close").addEventListener("click", function() {
                                    toast.hide();
                                });
                            });
                          </script>';
                } else {
                    echo '<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
                            <div id="errorToast" class="toast fade show bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                <div class="toast-body d-flex justify-content-between align-items-center">
                                    Erro: ' . $conexao->error . '
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                          </div>
                          <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                var toastEl = document.getElementById("errorToast");
                                var toast = new bootstrap.Toast(toastEl);
                                toast.show();
                                
                                toastEl.querySelector(".btn-close").addEventListener("click", function() {
                                    toast.hide();
                                });
                            });
                          </script>';
                }

                $conexao->close();
            }
            ?>




<style>
    .footer {
        background: linear-gradient(90deg, #2563eb 60%, #1e40af 100%);
        font-size: 1.05rem;
        letter-spacing: 0.2px;
        border-top-left-radius: 32px;
        border-top-right-radius: 32px;
        box-shadow: 0 -4px 32px rgba(30,64,175,0.08), 0 -1.5px 4px rgba(0,0,0,0.04);
    }
    .footer h5 {
        font-weight: 800;
        letter-spacing: 1px;
        color: #fff;
    }
    .footer .btn-link {
        color: #f3f4f6;
        font-weight: 500;
        padding-left: 0;
        transition: color 0.18s;
        text-align: left;
        display: block;
        margin-bottom: 0.4rem;
    }
    .footer .btn-link:hover {
        color: #ffd700;
        text-decoration: underline;
    }
    .footer .btn-social {
        width: 38px;
        height: 38px;
        margin-right: 0.5rem;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
        background: transparent;
        transition: background 0.18s, color 0.18s, border 0.18s;
    }
    .footer .btn-social:hover {
        background: #fff;
        color: #a80707 !important;
        border-color: #ffd700;
    }
    .footer .form-control,
    .footer textarea {
        background: #fff;
        border-radius: 8px;
        border: none;
        font-size: 1rem;
        color: #1e293b;
        box-shadow: 0 1px 4px rgba(30,64,175,0.06);
        margin-bottom: 0.5rem;
    }
    .footer .form-control:focus,
    .footer textarea:focus {
        box-shadow: 0 2px 8px #ffd70055;
        border: 1.5px solid #ffd700;
        outline: none;
    }
    .footer .btn-primary2 {
        background: linear-gradient(90deg, #2563eb 60%, #1e40af 100%);
        color: #fff;
        font-weight: 700;
        border: none;
        border-radius: 8px;
        transition: background 0.18s, color 0.18s;
    }
    .footer .btn-primary2:hover {
        background: linear-gradient(90deg, #1e40af 60%, #2563eb 100%);
        color: #fff;
    }
    .footer .copyright {
        border-top: 1.5px solid #fff2;
        padding-top: 1.2rem;
        margin-top: 1.5rem;
        font-size: 0.98rem;
        color: #f3f4f6;
    }
    .footer .copyright a {
        color: #ffd700;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.18s;
    }
    .footer .copyright a:hover {
        color: #fff;
        text-decoration: underline;
    }
    @media (max-width: 991.98px) {
        .footer {
            border-radius: 0;
        }
        .footer .copyright {
            font-size: 0.95rem;
        }
    }
</style>

<div class="container-fluid text-light footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Endereço e Contato -->
            <div class="col-lg-3 col-md-6">
                <h5 class="mb-4">Endereço</h5>
                <p class="mb-2">
                    <i class="fa fa-map-marker-alt me-2"></i>
                    Avenida Deolinda Rodrigues.<br>
                    Instituto Médio Comercial de Luanda (IMCL) <br>
                    Próximo à Estátua de Dr. António Agostinho Neto.
                </p>
                <p class="mb-2">
                    <i class="fa fa-phone-alt me-2"></i>
                    <a href="tel:+244942979525" style="color:#ffd700;text-decoration:none;">+244 942 979 525</a>
                </p>
                <div class="d-flex pt-2">
                    <a class="btn btn-outline-light btn-social rounded-circle" href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social rounded-circle" href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social rounded-circle" href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-outline-light btn-social rounded-circle" href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <!-- Cursos -->
            <div class="col-lg-3 col-md-6">
                <h5 class="mb-4">Cursos</h5>
                <a class="btn btn-link" href="cursos.php">Informática de Gestão</a>
                <a class="btn btn-link" href="cursos.php">Contabilidade de Gestão</a>
                <a class="btn btn-link" href="cursos.php">Finanças</a>
                <a class="btn btn-link" href="cursos.php">Secretariado</a>
                <a class="btn btn-link" href="cursos.php">Economia</a>
                <a class="btn btn-link" href="cursos.php">Gestão de Recursos Humanos</a>
            </div>
            <!-- Links Rápidos -->
            <div class="col-lg-3 col-md-6">
                <h5 class="mb-4">Links Rápidos</h5>
                <a class="btn btn-link" href="about.php">Sobre</a>
                <a class="btn btn-link" href="contact.php">Contactos</a>
                <a class="btn btn-link" href="cursos.php">Cursos</a>
                <a class="btn btn-link" href="noticia.php">Notícias</a>
                <a class="btn btn-link" href="evento.php">Eventos</a>
            </div>
            <!-- Comentário -->
            <div class="col-lg-3 col-md-6">
                <h5 class="mb-4">Envie seu Comentário</h5>
                <form method="POST" autocomplete="off">
                    <div class="mb-2">
                        <input type="text" class="form-control" name="nome" placeholder="Seu Nome"
                               value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>
                    </div>
                    <div class="mb-2">
                        <input type="email" class="form-control" name="email" placeholder="Seu Email"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>
                    <div class="mb-2">
                        <textarea class="form-control" name="comentario" rows="2" placeholder="Seu Comentário" required><?php 
                            echo isset($_POST['comentario']) ? htmlspecialchars($_POST['comentario']) : ''; 
                        ?></textarea>
                    </div>
                    <button type="submit" name="nEnvair" class="btn btn-primary2 w-100 py-2 mt-1">
                        <i class="fa fa-paper-plane me-2"></i>Enviar
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="copyright">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    &copy; <a class="border-bottom" href="#">Grupo do Sérgio</a>, Todos os direitos reservados.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a class="border-bottom" href="admin/index">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->


<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>


<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/counterup/counterup.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/tempusdominus/js/moment.min.js"></script>
<script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
