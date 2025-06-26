<?php
include_once "header.php";
include_once "container_pricipal.php";
include_once "menulateral.php";
include_once "models/conexao.php";
include_once "models/Cursos.php";

if (isset($_SESSION["nome_completo"])) :
    if (isset($_GET['id'])) :
        $cursoId = $_GET['id'];

        // Cria a instância da classe Cursos e recupera os dados do curso
        $cursos = new Cursos($conexao);
        $curso = $cursos->readById($cursoId);
        if (!$curso) {
            echo "<div class='alert alert-danger'>Curso não encontrado.</div>";
            exit;
        }

        // Verifica se o formulário foi submetido
        if (isset($_POST['atualizar'])) {
            // Captura os dados do formulário
            $nome = $_POST['nome'];
            $faculdade = $_POST['faculdade'];
            $duracao = $_POST['duracao'];
            $inicio = $_POST['inicio'];
            $fim = $_POST['fim'];
            $total_vaga = $_POST['total_vaga'];
            $estado = $_POST['estado'];
            $img_banner = $_FILES['img_banner']['name'];



            // Atualiza o curso
            $updateStatus = $cursos->updateCourse($cursoId, $nome, $faculdade, $duracao, $inicio, $fim, $total_vaga, $estado, $img_banner);

            if ($updateStatus) {

                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Curso atualizado com sucesso!',
                        text: 'Redirecionando para a lista de faculdades...'
                    }).then(function() {
                        window.location = 'adminListarCursos.php';
                    });
                  </script>";


            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao atualizar curso. Tente novamente.',
                        text: 'Erro'
                    });
                  </script>";

            }
        }
    endif;
    ?>

    <!--== BODY INNER CONTAINER ==-->
    <div class="sb2-2">
        <!--== breadcrumbs ==-->
        <div class="sb2-2-2">
            <ul>
                <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Painel</a></li>
                <li><a href="#">Painel</a></li>
                <li class="active-bre"><a href="#">Editar Curso</a></li>
            </ul>
        </div>

        <!--== Formulário de Edição de Curso ==-->
        <div class="sb2-2-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-inn-sp admin-form">
                        <div class="inn-title">
                            <h4>Editar Curso</h4>
                        </div>
                        <div class="bor">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col s12">
                                        <label for="nome" style="font-weight: bold">Nome do Curso</label>
                                        <input type="text" name="nome" class="validate" id="nome"
                                               value="<?php echo htmlspecialchars($curso['nome']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="faculdade" style="font-weight: bold">Faculdade</label>
                                        <input type="text" name="faculdade" class="validate" id="faculdade"
                                               value="<?php echo htmlspecialchars($curso['faculdade']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="duracao" style="font-weight: bold">Duração</label>
                                        <input type="text" name="duracao" class="validate" id="duracao"
                                               value="<?php echo htmlspecialchars($curso['duracao']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inicio" style="font-weight: bold">Data de Início</label>
                                        <input type="date" name="inicio" class="validate" id="inicio"
                                               value="<?php echo $curso['inicio']; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fim" style="font-weight: bold">Data de Término</label>
                                        <input type="date" name="fim" class="validate" id="fim"
                                               value="<?php echo $curso['fim']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_vaga" style="font-weight: bold">Total de Vagas</label>
                                        <input type="number" name="total_vaga" class="validate" id="total_vaga"
                                               value="<?php echo $curso['total_vaga']; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estado" style="font-weight: bold">Status</label>
                                        <select name="estado"  id="estado" class="validate" required>
                                            <option value="Ativo" <?php echo ($curso['estado'] == 'Ativo') ? 'selected' : ''; ?>>
                                                Ativo
                                            </option>
                                            <option value="Inativo" <?php echo ($curso['estado'] == 'Inativo') ? 'selected' : ''; ?>>
                                                Inativo
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="font-weight: bold">
                                        <label for="img_banner">Imagem do Banner</label>
                                        <input type="file" name="img_banner" class="form-control" id="img_banner">
                                        <small>Imagem atual: <img
                                                    src="<?php echo htmlspecialchars($curso['img_banner']); ?>"
                                                    style="max-width: 200px;"></small>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="atualizar" class="btn btn-primary">Atualizar Curso</button>
                        </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Import jQuery before materialize.js-->
    <script src="js/main.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <script src="js/custom.js"></script>

    </body>
    </html>

<?php
else:
    echo "<script>window.location='index.php'</script>";
endif;
?>

