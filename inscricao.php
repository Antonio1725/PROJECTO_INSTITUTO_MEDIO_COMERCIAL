<!DOCTYPE html>
<html lang="pt">

<head>
    <?php
    include_once "head.php";
  
    ?>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .form-label{
            font-weight: bold;
        }
        .invalid-feedback {
            display: none;
            color: #dc3545;
        }
        .is-invalid + .invalid-feedback {
            display: block;
        }
    </style>


    <script>
        window.onload = function () {
            const dataInput = document.getElementById("dataNascimento");

            const hoje = new Date();
            const anoMaximo = hoje.getFullYear() - 15;
            const mes = (hoje.getMonth() + 1).toString().padStart(2, '0');
            const dia = hoje.getDate().toString().padStart(2, '0');

            const dataMaxima = `${anoMaximo}-${mes}-${dia}`;
            dataInput.max = dataMaxima;
        };
    </script>


</head>

<body>
<?php

include_once "menu_principal.php";

include_once "admin/models/conexao.php"
?>



<div class="container mt-5">
    <div class="mb-4 text-end">
        <a href="consultarInscricao.php" class="btn  btn-primary">Consultar Inscrição</a>
    </div>

    <?php
    // Verifica se a inscrição está disponível
    $inscricao_disponivel = false;
    $sql_control = "SELECT controlInscricao FROM controlo_links ORDER BY id DESC LIMIT 1";
    $res_control = $conexao->query($sql_control);
    if ($res_control && $row_control = $res_control->fetch_assoc()) {
        if ($row_control['controlInscricao'] === 'S') {
            $inscricao_disponivel = true;
        }
    }
    ?>

    <?php if ($inscricao_disponivel): ?>
    <div class="card shadow-lg p-4 p-md-5 border-0 rounded-4" style="background-color: #f8f9fa;">
        <h2 class="text-center mb-4 fw-bold text-primary">Formulário de Inscrição</h2>
        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>

            <h4 class="mb-3 mt-4 border-bottom pb-2">Dados Pessoais</h4>
            <div class="row">
                <!-- Nome do Candidato -->
                <div class="col-12 mb-3">
                    <label for="nomeCandidato" class="form-label">Nome Completo do Candidato</label>
                    <input type="text" class="form-control" placeholder="Digite o seu nome completo" id="nomeCandidato" name="nomeCandidato" required="Campo Obrigatório">
                    <div class="invalid-feedback">Por favor, insira o seu nome completo.</div>
                </div>

                <!-- Data de Nascimento -->
                <div class="col-md-6 mb-3">
                    <label for="dataNascimento" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="dataNascimento" name="dataNascimento" required title="Você deve ter pelo menos 15 anos">
                    <div class="invalid-feedback">Por favor, insira uma data de nascimento válida.</div>
                </div>

                <!-- B.I -->
                <div class="col-md-6 mb-3">
                    <label for="bi" class="form-label">Nº do Bilhete de Identidade (B.I)</label>
                    <input type="text" class="form-control" placeholder="Ex: 007817482LA040" pattern="^\d{9}[A-Z]{2}\d{3}$" title="Insira um BI válido: 9 números, 2 letras maiúsculas e 3 números (ex: 007817482LA040)" id="bi" name="bi" required>
                    <div class="invalid-feedback">Insira um BI válido (ex: 007817482LA040).</div>
                </div>

                <!-- Gênero -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gênero</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="genero" id="generoMasculino" value="M" required>
                            <label class="form-check-label" for="generoMasculino">Masculino</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="genero" id="generoFeminino" value="F" required>
                            <label class="form-check-label" for="generoFeminino">Feminino</label>
                        </div>
                    </div>
                    <div class="invalid-feedback">Por favor, selecione o seu gênero.</div>
                </div>

                <!-- Nacionalidade -->
                <div class="col-md-6 mb-3">
                    <label for="nacionalidade" class="form-label">Nacionalidade</label>
                    <input type="text" class="form-control" placeholder="Digite a sua nacionalidade" id="nacionalidade" name="nacionalidade" required>
                    <div class="invalid-feedback">Campo obrigatório.</div>
                </div>
            </div>

            <h4 class="mb-3 mt-4 border-bottom pb-2">Morada e Naturalidade</h4>
            <div class="row">
                <!-- Naturalidade -->
                <div class="col-md-6 mb-3">
                    <label for="naturalDe" class="form-label">Natural de</label>
                    <input type="text" class="form-control" placeholder="Digite a sua naturalidade" id="naturalDe" name="naturalDe" required>
                    <div class="invalid-feedback">Campo obrigatório.</div>
                </div>

                <!-- Província -->
                <div class="col-md-6 mb-3">
                    <label for="provincia" class="form-label">Província</label>
                    <input type="text" class="form-control" placeholder="Digite a sua Província" id="provincia" name="provincia" required>
                    <div class="invalid-feedback">Campo obrigatório.</div>
                </div>

                <!-- Município -->
                <div class="col-md-6 mb-3">
                    <label for="municipio" class="form-label">Município</label>
                    <input type="text" class="form-control" placeholder="Digite o seu Município" id="municipio" name="municipio" required>
                    <div class="invalid-feedback">Campo obrigatório.</div>
                </div>

                <!-- Morada Atual -->
                <div class="col-md-6 mb-3">
                    <label for="morada" class="form-label">Morada Atual</label>
                    <input type="text" class="form-control" placeholder="Digite a sua morada atual" id="morada" name="morada" required>
                    <div class="invalid-feedback">Campo obrigatório.</div>
                </div>
            </div>

            <h4 class="mb-3 mt-4 border-bottom pb-2">Filiação e Contatos</h4>
            <div class="row">
                <!-- Nome da Mãe -->
                <div class="col-md-6 mb-3">
                    <label for="nomeMae" class="form-label">Nome da Mãe</label>
                    <input type="text" class="form-control" placeholder="Digite o nome da Mãe" id="nomeMae" name="nomeMae" required>
                    <div class="invalid-feedback">Campo obrigatório.</div>
                </div>

                <!-- Nome do Pai -->
                <div class="col-md-6 mb-3">
                    <label for="nomePai" class="form-label">Nome do Pai</label>
                    <input type="text" class="form-control" placeholder="Digite o nome do Pai" id="nomePai" name="nomePai" required>
                    <div class="invalid-feedback">Campo obrigatório.</div>
                </div>

                <!-- Nome do Encarregado de Educação -->
                <div class="col-md-6 mb-3">
                    <label for="encarregado" class="form-label">Nome do Encarregado de Educação</label>
                    <input type="text" class="form-control" placeholder="Digite o nome do encarregado" id="encarregado" name="encarregado" required>
                    <div class="invalid-feedback">Campo obrigatório.</div>
                </div>

                <!-- Terminal do Candidato -->
                <div class="col-md-6 mb-3">
                    <label for="terminal" class="form-label">Telefone do Candidato</label>
                    <input type="tel" class="form-control" placeholder="Digite o seu número de telefone" id="terminal" name="terminal" required>
                    <div class="invalid-feedback">Campo obrigatório.</div>
                </div>

                <!-- Email -->
                <div class="col-12 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" placeholder="Digite o seu email" id="email" name="email">
                    <small class="form-text text-muted">Se não tiver email, deixe este campo em branco.</small>
                    <div class="invalid-feedback">Por favor, insira um email válido.</div>
                </div>
            </div>

            <h4 class="mb-3 mt-4 border-bottom pb-2">Opções de Curso</h4>
            <div class="row">
                <!-- 1ª Opção de Curso -->
                <div class="col-md-6 mb-3">
                    <label for="curso1" class="form-label">1ª Opção de Curso</label>
                    <select class="form-select" id="curso1" name="curso1" required>
                        <option value="" selected disabled>Selecione a primeira opção</option>
                        <option value="Informática de Gestão">Informática de Gestão</option>
                        <option value="Comércio">Comércio</option>
                        <option value="Secretariado">Secretariado</option>
                        <option value="Finanças">Finanças</option>
                        <option value="Contabilidade">Contabilidade</option>
                        <option value="Gestão de Recursos Humanos">Gestão de Recursos Humanos</option>
                    </select>
                    <div class="invalid-feedback">Por favor, selecione a 1ª opção de curso.</div>
                </div>

                <!-- 2ª Opção de Curso -->
                <div class="col-md-6 mb-3">
                    <label for="curso2" class="form-label">2ª Opção de Curso</label>
                    <select class="form-select" id="curso2" name="curso2" required>
                        <option value="" selected disabled>Selecione a segunda opção</option>
                        <option value="Informática de Gestão">Informática de Gestão</option>
                        <option value="Comércio">Comércio</option>
                        <option value="Secretariado">Secretariado</option>
                        <option value="Finanças">Finanças</option>
                        <option value="Contabilidade">Contabilidade</option>
                        <option value="Gestão de Recursos Humanos">Gestão de Recursos Humanos</option>
                    </select>
                    <div class="invalid-feedback">Por favor, selecione a 2ª opção de curso.</div>
                </div>
            </div>

            <!-- Botão de envio -->
            <div class="mt-4 pt-2 text-center">
                <button type="submit" name="cadastrar" class="btn btn-primary btn-lg px-5">Enviar Inscrição</button>
            </div>
        </form>
    </div>
    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            As inscrições não estão disponíveis no momento. Por favor, volte mais tarde.
        </div>
    <?php endif; ?>


</div>








<!-- Testimonial End -->


<?php
include_once "footer.php";

?>
</body>

<?php

// Inclua o PHPMailer (ajuste o caminho conforme necessário)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

$conn = $conexao;

// Verifica se o formulário foi enviado
if (isset($_POST['cadastrar'])) {
    // Recebe os dados do formulário
    $nome = $conn->real_escape_string($_POST['nomeCandidato']);
    $data_nascimento = $conn->real_escape_string($_POST['dataNascimento']);
    $bi = $conn->real_escape_string($_POST['bi']);
    $provincia = $conn->real_escape_string($_POST['provincia']);
    $municipio = $conn->real_escape_string($_POST['municipio']);
    $natural_de = $conn->real_escape_string($_POST['naturalDe']);
    $morada = $conn->real_escape_string($_POST['morada']);
    $nacionalidade = $conn->real_escape_string($_POST['nacionalidade']);
    $email = $conn->real_escape_string($_POST['email']);
    $genero = $conn->real_escape_string($_POST['genero']);
    $nome_encarregado = $conn->real_escape_string($_POST['encarregado']);
    $terminal = $conn->real_escape_string($_POST['terminal']);
    $nome_mae = $conn->real_escape_string($_POST['nomeMae']);
    $nome_pai = $conn->real_escape_string($_POST['nomePai']);
    $curso_1_opcao = $conn->real_escape_string($_POST['curso1']);
    $curso_2_opcao = $conn->real_escape_string($_POST['curso2']);

    // Validação da idade mínima de 15 anos
    $data_nascimento_timestamp = strtotime($data_nascimento);
    $data_limite = strtotime('-15 years');
    if ($data_nascimento_timestamp > $data_limite) {
        echo "<script>Swal.fire({title: 'Erro!', text: 'Você deve ter pelo menos 15 anos para se inscrever.', icon: 'error', confirmButtonText: 'OK'});</script>";
        exit();
    }

    // Validação do BI (regex)
    if (!preg_match('/^\d{9}[A-Z]{2}\d{3}$/', $bi)) {
        echo "<script>Swal.fire({title: 'Erro!', text: 'O número do BI deve ser no formato 007817482LA040.', icon: 'error', confirmButtonText: 'OK'});</script>";
        exit();
    }

    // Verifica duplicidade de BI
    $sql = "SELECT * FROM aluno_inscricao WHERE bi ='$bi'";
    $R = mysqli_query($conn, $sql);
    $numLinha = mysqli_num_rows($R);
    if($numLinha != 0){
        echo "<script>Swal.fire({title: 'Erro!', text: 'Já existe uma inscrição com este número de BI.', icon: 'error', confirmButtonText: 'OK'});</script>";
        exit();
    }

    // SQL para inserir os dados na tabela
    $sql = "INSERT INTO aluno_inscricao 
            (nome, data_nascimento, bi, provincia, municipio, natural_de, morada, nacionalidade, email, genero, 
              nome_encarregado, terminal, nome_mae, nome_pai, curso_1_opcao, curso_2_opcao) 
            VALUES 
            ('$nome', '$data_nascimento', '$bi', '$provincia', '$municipio', '$natural_de', '$morada', '$nacionalidade', 
             '$email', '$genero',  '$nome_encarregado', '$terminal', '$nome_mae', 
             '$nome_pai', '$curso_1_opcao', '$curso_2_opcao')";

    if ($conn->query($sql) === TRUE) {
        $ultimo_id = $conn->insert_id;
        // Enviar email se informado
        if (!empty($email)) {
            $assunto = 'Confirmação de Inscrição';
            $mensagem = "Olá $nome,<br><br>Sua inscrição foi realizada com sucesso!<br><br>Obrigado.";
            try {
                $mail = new PHPMailer();
                // Configurações do servidor SMTP local
                $mail->isSMTP(true);
                $mail->Host = 'smtp.mailsend.net';
                $mail->Port = 587;
                $mail->Username = 'MS_c5LmDC@test-51ndgwvzx9dlzqx8.mlsender.net';
                $mail->Password = 'mssp.ccIPERk.0r83ql3v1vpgzw1j.NKOvAMA';
                $mail->SMTPAuth = true;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom('teste-51ndgwvzx9dlzqx8.mlsender.net' . $_SERVER['SERVER_NAME'], 'Instituto Médio Comercial de Luanda');
                $mail->addAddress($email, $nome);

                $mail->isHTML(true);
                $mail->Subject = $assunto;
                $mail->Body = $mensagem;

                $mail->send();
            } catch (Exception $e) {
                // Você pode logar o erro se quiser
                // error_log('Erro ao enviar email: ' . $mail->ErrorInfo);
            }
        }
        ?>
        <script>
            Swal.fire({
                title: 'Sucesso!',
                text: 'Inscrição realizada com sucesso!',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Imprimir Recibo',
                cancelButtonText: 'Fechar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('impressao/gerar_recibo.php?id=<?php echo $ultimo_id; ?>', '_blank');
                }
            });
        </script>
        <?php
    } else {
        ?>
        <script>
            Swal.fire({
                title: 'Erro!',
                text: 'Erro ao realizar a inscrição',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
        <?php
    }
}

// Fecha a conexão com o banco
$conn->close();
?>

<script>
    // Bootstrap validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    // Adiciona a classe de validação visual
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>

</html>
