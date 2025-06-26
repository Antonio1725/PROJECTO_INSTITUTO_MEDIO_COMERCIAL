<?php
include_once "header.php";
include_once "container_pricipal.php";
include_once "menulateral.php";

include_once "models/conexao.php";

if (isset($_SESSION["nome_completo"])) {
    ?>


    <?php


    $conn = $conexao;

// Verificar se o ID foi passado e é válido
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']);

        // Buscar aluno pelo ID
        $query = "SELECT * FROM alunos WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $aluno = $result->fetch_assoc();
        } else {
            $erro = "Aluno não encontrado.";
        }
        $stmt->close();
    } else {
        $erro = "ID inválido.";
    }

// Processar o formulário de edição
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $aluno) {
        // Capturar dados do formulário
        $nome = $_POST['nome'] ?? $aluno['nome'];
        $email = $_POST['email'] ?? $aluno['email'];
        $telefone = $_POST['telefone'] ?? $aluno['telefone'];
        $dataNascimento = $_POST['dataNascimento'] ?? $aluno['DataNascimento'];
        $documentoMilitar = $_POST['documentoMilitar'] ?? $aluno['DocumentoMilitar'];
        $bi = $_POST['bi'] ?? $aluno['BI'];
        $morada = $_POST['morada'] ?? $aluno['Morada'];
        $tel1 = $_POST['tel1'] ?? $aluno['Tel1'];
        $tel2 = $_POST['tel2'] ?? $aluno['Tel2'];
        $curso = $_POST['curso'] ?? $aluno['Curso'];
        $turno = $_POST['turno'] ?? $aluno['Turno'];
        $sexo = $_POST['sexo'] ?? $aluno['Sexo'];
        $estadoCivil = $_POST['estadoCivil'] ?? $aluno['EstadoCivil'];
        $nacionalidade = $_POST['nacionalidade'] ?? $aluno['Nacionalidade'];
        $bolsa = $_POST['bolsa'] ?? $aluno['bolsa'];
        $trabalhador = $_POST['trabalhador'] ?? $aluno['Trabalhador'];
        $estado = $_POST['estado'] ?? $aluno['Estado'];

        // Atualizar dados no banco
        $query = "UPDATE alunos 
              SET nome = ?, email = ?, telefone = ?, DataNascimento = ?, DocumentoMilitar = ?, BI = ?, Morada = ?, Tel1 = ?, Tel2 = ?, 
                  Curso = ?, Turno = ?, Sexo = ?, EstadoCivil = ?, Nacionalidade = ?, bolsa = ?, Trabalhador = ?, Estado = ?
              WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssssissssssssi", $nome, $email, $telefone, $dataNascimento, $documentoMilitar, $bi, $morada, $tel1, $tel2, $curso, $turno, $sexo, $estadoCivil, $nacionalidade, $bolsa, $trabalhador, $estado, $id);

        if ($stmt->execute()) {
            echo "<script>
                            Swal.fire({
                                title: 'Successo!',
                                text: 'Dados do aluno atualizados com sucesso!',
                                icon: 'success'
                            });
                        </script>";

        } else {
            echo "<script>
                            Swal.fire({
                                title: 'Erro',
                                text: 'Erro ao atualizar os dados do aluno.',
                                icon: 'error'
                            });
                        </script>";
        }
        $stmt->close();
    }

    $conn->close();
    ?>


    <!--== CORPO INTERNO RECIPIENTE ==-->
    <div class="sb2-2">
        <!--== breadcrumbs ==-->
        <div class="sb2-2-2">
            <ul>
                <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Painel</a>
                </li>
                <li class="active-bre"><a href="#"> Editar Aluno</a>
                </li>

            </ul>
        </div>


        <?php if ($erro): ?>
            <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <p style="color: green;"><?= htmlspecialchars($sucesso) ?></p>
        <?php endif; ?>

        <?php if ($aluno): ?>
        <!--== User Details ==-->
        <div class="sb2-2-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-inn-sp admin-form">
                        <div class="inn-title">
                            <h4>Editar Alunos</h4>
                        </div>
                        <div class="tab-inn">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="nome" style="font-weight: bold;">Nome:</label>
                                        <input type="text" id="nome" readonly name="nome"
                                               value="<?= htmlspecialchars($aluno['nome']) ?>"
                                               required><br><br>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" style="font-weight: bold;">Email:</label>
                                        <input type="email" id="email" readonly name="email"
                                               value="<?= htmlspecialchars($aluno['email']) ?>" required><br><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="dataNascimento" style="font-weight: bold;">Data de
                                            Nascimento:</label>
                                        <input type="text" id="dataNascimento" readonly name="dataNascimento"
                                               value="<?= htmlspecialchars($aluno['DataNascimento']) ?>"><br><br>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="documentoMilitar" style="font-weight: bold;">Documento Militar:</label>
                                        <input type="text" readonly id="documentoMilitar" name="documentoMilitar"
                                               value="<?= htmlspecialchars($aluno['DocumentoMilitar']) ?>"><br><br>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                <label for="bi" style="font-weight: bold;">BI:</label>
                                <input type="text" id="bi" readonly name="bi"
                                       value="<?= htmlspecialchars($aluno['BI']) ?>"><br><br>
                                    </div>
                                    <div class="col-md-6">
                                <label for="morada" style="font-weight: bold;">Morada:</label>
                                <input type="text" id="morada" readonly name="morada"
                                       value="<?= htmlspecialchars($aluno['Morada']) ?>"><br><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                <label for="tel1" style="font-weight: bold;">Telefone 1:</label>
                                <input type="text" id="tel1" readonly name="tel1"
                                       value="<?= htmlspecialchars($aluno['Tel1']) ?>"><br><br>
                                    </div>
                                    <div class="col-md-6">
                                <label for="tel2" style="font-weight: bold;">Telefone 2:</label>
                                <input type="text" id="tel2" readonly name="tel2"
                                       value="<?= htmlspecialchars($aluno['Tel2']) ?>"><br><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                <label for="curso" style="font-weight: bold;">Curso:</label>
                                <input type="text" id="curso" readonly name="curso"
                                       value="<?= htmlspecialchars($aluno['Curso']) ?>"><br><br>
                                    </div>
                                    <div class="col-md-6">
                                <label for="turno" style="font-weight: bold;">Turno:</label>
                                <input type="text" id="turno" readonly name="turno"
                                       value="<?= htmlspecialchars($aluno['Turno']) ?>"><br><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                <label for="sexo" style="font-weight: bold;">Sexo:</label>
                                <input type="text" id="sexo" readonly name="sexo"
                                       value="<?= htmlspecialchars($aluno['Sexo']) ?>"><br><br>
                                    </div>
                                    <div class="col-md-6">
                                <label for="estadoCivil" style="font-weight: bold;">Estado Civil:</label>
                                <input type="text" id="estadoCivil" readonly name="estadoCivil"
                                       value="<?= htmlspecialchars($aluno['EstadoCivil']) ?>"><br><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                <label for="nacionalidade" style="font-weight: bold;">Nacionalidade:</label>
                                <input type="text" id="nacionalidade" readonly name="nacionalidade"
                                       value="<?= htmlspecialchars($aluno['Nacionalidade']) ?>"><br><br>
                                    </div>
                                    <div class="col-md-6">

                                <label for="bolsa" style="font-weight: bold;">Bolsa:</label>
                                <input type="text" id="bolsa" readonly name="bolsa"
                                       value="<?= htmlspecialchars($aluno['bolsa']) ?>"><br><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                <label for="trabalhador" style="font-weight: bold;">Trabalhador:</label>
                                <input type="text" id="trabalhador" readonly name="trabalhador"
                                       value="<?= htmlspecialchars($aluno['Trabalhador']) ?>"><br><br>
                                    </div>
                                    <div class="col-md-6">
                                <label for="estado" style="font-weight: bold;">Estado:</label>
                                <select id="estado" name="estado">
                                    <option></option>
                                    <option value="Apto" <?= $aluno['Estado'] === 'Apto' ? 'selected' : '' ?>>Apto
                                    </option>
                                    <option value="Não Apto" <?= $aluno['Estado'] === 'Não Apto' ? 'selected' : '' ?>>
                                        Não Apto
                                    </option>
                                </select><br><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <button type="submit" class="btn btn-success">Atualizar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>


    <!--Import jQuery before materialize.js-->
    <script src="js/main.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <script src="js/custom.js"></script>

    <?php
} else {
    echo "<script>window.location='index.php'</script>";
}

?>
