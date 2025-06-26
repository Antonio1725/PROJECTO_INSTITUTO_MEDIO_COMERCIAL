<?php
include_once "header.php";
include_once "container_pricipal.php";
include_once "menulateral.php";
include_once "models/conexao.php";
include_once "models/Cursos.php";
if(isset($_SESSION["nome_completo"])){
?>
            <!--== BODY INNER CONTAINER ==-->
            <div class="sb2-2">
                <!--== breadcrumbs ==-->
                <div class="sb2-2-2">
                    <ul>
                        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Início</a>
                        </li>
                        <li class="active-bre"><a href="#"> Painel</a>
                        </li>
                        
                    </ul>
                </div>

                <!--== Detalhes do Curso ==-->
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                    <h4>Detalhes do curso</h4>
                                </div>
                                <?php
// Cria a conexão com o banco de dados

// Verifica se a conexão foi bem-sucedida


// Cria uma instância da classe Cursos
$cursos = new Cursos($conexao);

// Chama o método readAll para obter todos os cursos
$listaCursos = $cursos->readAll();
$status = isset($_GET['status']) ? $_GET['status'] : null;

?>

<div class="tab-inn">
    <div class="table-responsive table-desi">
        <!-- Exibe a mensagem de status -->
<?php if ($status === 'success') : ?>
    <div class="alert alert-success">Curso excluído com sucesso!</div>
<?php elseif ($status === 'error') : ?>
    <div class="alert alert-danger">Ocorreu um erro ao excluir o curso.</div>
<?php elseif ($status === 'invalid') : ?>
    <div class="alert alert-warning">ID do curso inválido.</div>
<?php endif; ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Curso</th>
                    <th>Faculdade</th>
                    <th>Duração</th>
                    <th>Data de início</th>
                    <th>Data de término</th>
                    <th>Total de vagas</th>
                    <th>Status</th>
                    <th style="width: 170px">Opção</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($listaCursos) > 0) {
                    foreach ($listaCursos as $curso) {
                        // Formata as datas para exibição
                        $inicio = date('d/m/Y', strtotime($curso["inicio"]));
                        $fim = date('d/m/Y', strtotime($curso["fim"]));

                        // Calcula a duração do curso
                        $inicioDate = new DateTime($curso["inicio"]);
                        $fimDate = new DateTime($curso["fim"]);
                        $interval = $inicioDate->diff($fimDate);
                        $duracao = $interval->format('%m meses'); // Exibe a duração em meses

                        // Exibe os dados do curso na tabela
                        ?><tr>
                                <td><img src="<?php echo htmlspecialchars($curso['img_banner']); ?>" style='border-radius: 25px;width:50px;height:50px' alt='Banner do curso'></td>
                                <td><?php echo htmlspecialchars($curso['nome']); ?></td>
                                <td><?php echo htmlspecialchars( $curso['faculdade']); ?></td>
                                <td><?php echo htmlspecialchars($curso['duracao']); ?></td>
                                <td><?php echo htmlspecialchars($curso['inicio']); ?></td>
                                <td><?php echo htmlspecialchars($curso['fim']); ?></td>
                                <td><?php echo htmlspecialchars($curso['total_vaga']); ?></td>
                                <td><?php echo htmlspecialchars($curso['estatus']); ?></td>

                            <td class="btn-group">
                                <a href="editar_curso.php?id=<?php echo $curso['id']; ?>" class="btn btn-sm btn-warning" style="color: white; margin-right: 5px;">Editar</a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger" style="color: white;" onclick="deleteCourse(<?php echo $curso['id']; ?>)">DELETAR</a>
                            </td>

                                </tr>
                        <!-- Adicionando SweetAlert2 -->


                        <script type="text/javascript">
                            function deleteCourse(courseId) {
                                Swal.fire({
                                    title: 'Tem certeza que deseja excluir este curso?',
                                    text: "Essa ação não poderá ser desfeita!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Sim, excluir!',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redireciona para a página de exclusão
                                        window.location.href = 'models/classAux/excluir_curso.php?id=' + courseId;
                                    }
                                });
                            }
                        </script>


                                <?php
                    }
                } else {
                    echo "<tr><td colspan='9'>Nenhum curso encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
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
}else{
    echo "<script>window.location='index.php'</script>";
}




?>