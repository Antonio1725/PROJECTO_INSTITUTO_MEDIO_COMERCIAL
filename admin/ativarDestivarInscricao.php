<?php
include_once "header.php";
include_once "container_pricipal.php";
include_once "menulateral.php";
include_once "models/Eventos.php";
include_once "models/Conexao.php";
if (isset(
    $_SESSION["nome_completo"]
)) {

    $id_usuario = $_SESSION["id_usuario"];

    // Buscar os valores atuais
    $sql = "SELECT controlNota, controlExame, controlInscricao FROM controlo_links WHERE id = 1";
    $query =  mysqli_query($conexao, $sql);
    $resultado = mysqli_fetch_assoc($query);
    $controlNota  = $resultado['controlNota'];
    $controlExame  = $resultado['controlExame'];
    $controlInscricao  = $resultado['controlInscricao'];
?>

<!--== BODY INNER CONTAINER ==-->
<div class="sb2-2">
    <!--== breadcrumbs ==-->
    <div class="sb2-2-2">
        <ul>
            <li><a href="admin.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li> <li class="active-bre"><a href="#">Ativar ou Desativar Opções</a></li>
        </ul>
    </div>

    <!--== User Details ==-->
    <div class="sb2-2-3">
        <div class="row">
            <div class="col-md-12">
                <div class="box-inn-sp admin-form">
                    <div class="inn-title">
                        <h4>Ativar ou Desativar Opções</h4>
                    </div>

                    <div class="tab-inn">
                        <form method="post" enctype="multipart/form-data">
                            <div class="row">
                                <label style="font-weight: bold">Escolha qual opção deseja ativar</label>
                                <div class="input-field col s12">
                                    <select name="opcao_ativa">
                                        <option value="controlNota" <?php if(
                                            $controlNota == 'S') echo 'selected'; ?>>Nota</option>
                                        <option value="controlExame" <?php if(
                                            $controlExame == 'S') echo 'selected'; ?>>Exame</option>
                                        <option value="controlInscricao" <?php if(
                                            $controlInscricao == 'S') echo 'selected'; ?>>Inscrição</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="waves-effect waves-light btn-large waves-input-wrapper">
                                        <input type="submit" name="salvar" value="Salvar" class="waves-button-input"></i>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php

    if (isset($_POST['salvar'])) {
        $opcao_ativa = $_POST['opcao_ativa'];
        // Montar o update para ativar apenas a escolhida
        $valores = [
            'controlNota' => 'N',
            'controlExame' => 'N',
            'controlInscricao' => 'N'
        ];
        $valores[$opcao_ativa] = 'S';
        $sql = "UPDATE controlo_links SET controlNota='{$valores['controlNota']}', controlExame='{$valores['controlExame']}', controlInscricao='{$valores['controlInscricao']}' WHERE id = 1";
        $v =  mysqli_query($conexao, $sql);
        if ($v) {
            echo "<script>alert('Status atualizado com sucesso!');window.location='ativarDestivarInscricao.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar status');</script>";
        }
    }
?>

<!--Import jQuery before materialize.js-->
<script src="js/main.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/materialize.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
<?php
} else {
    echo "<script>window.location='index.php'</script>";
}
?> 