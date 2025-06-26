
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "head.php";
    ?>
    <style>
        /* Card de Evento */
        .evento-card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 25px;
            max-width: 600px;
            width: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .evento-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }
        .evento-imagem-container {
            position: relative;
            height: 250px;
            overflow: hidden;
        }
        .evento-imagem {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .evento-card:hover .evento-imagem {
            transform: scale(1.1);
        }
        .evento-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.6));
        }
        .evento-conteudo {
            padding: 20px;
        }
        .evento-titulo {
            font-size: 1.75rem;
            margin-bottom: 10px;
            color: #333;
        }
        .evento-descricao {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .evento-info {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .evento-data, .evento-local {
            font-size: 0.95rem;
            color: #777;
            display: flex;
            align-items: center;
        }
        .evento-data::before {
            content: '📅';
            margin-right: 5px;
        }
        .evento-local::before {
            content: '📍';
            margin-right: 5px;
        }
        .evento-botao {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .evento-botao:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
    </style>
    <style>
        .nota-azul {
            color: blue;
            font-weight: bold;
        }
        .nota-vermelha {
            color: red;
            font-weight: bold;
        }
        .btn-matricula {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 24px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-matricula:hover {
            background-color: #218838;
            color: #fff;
            transform: translateY(-2px);
        }
        .msg-matricula-realizada {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 24px;
            background-color: #ffc107;
            color: #212529;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
<?php
include_once "menu_principal.php";
include_once "admin/models/conexao.php";
?>

<!-- Consulta de Nota e Resultado de Aptidão -->
<div class="container-xxl py-5">
    <div class="container">

        <?php
        $conn = $conexao;

        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtém o número do B.I do formulário
            $bi = trim($_POST['bi']);

            // Consulta para buscar dados do candidato, nota e resultado de aptidão
            $sql = "SELECT 
                        ai.id as idAluno,
                        ai.nome as nome,
                        ai.curso_1_opcao as curso,
                        ai.nota as nota,
                        ai.obs as resultado_aptidao,
                        ai.BI as bi
                    FROM aluno_inscricao ai
                    WHERE ai.BI = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $bi);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $dados = $result->fetch_assoc();

                // Verifica se já existe matrícula para este candidato
                $sqlMatricula = "SELECT id FROM matricula WHERE id_inscricao = ?";
                $stmtMatricula = $conn->prepare($sqlMatricula);
                $stmtMatricula->bind_param("i", $dados['idAluno']);
                $stmtMatricula->execute();
                $resultMatricula = $stmtMatricula->get_result();
                $ja_matriculado = ($resultMatricula && $resultMatricula->num_rows > 0);
                $stmtMatricula->close();
            } else {
                $mensagem = "Nenhum resultado encontrado para o B.I informado.";
            }

            $stmt->close();
        }
        ?>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm p-4 mb-5" style="border-radius: 15px;">
                        <div class="card-body">
                            <h2 class="text-center mb-3 fw-bold text-primary">Consultar Nota e Resultado de Aptidão</h2>
                            <p class="text-center text-muted mb-4">Para verificar sua nota e resultado de aptidão, por favor, insira o número do seu Bilhete de Identidade abaixo.</p>
                            <form method="post" class="needs-validation" novalidate>
                                <div class="input-group input-group-lg mb-3">
                                    <span class="input-group-text bg-light border-end-0" id="bi-addon"><i class="fa fa-id-card text-primary"></i></span>
                                    <input type="text" class="form-control" name="bi" placeholder="Ex: 007817482LA040" required pattern="^\d{9}[A-Z]{2}\d{3}$" title="Insira um BI válido: 9 números, 2 letras maiúsculas e 3 números (ex: 007817482LA040)" aria-describedby="bi-addon">
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-search me-2"></i>Consultar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isset($dados)): ?>
                <div class="card shadow-lg border-0" style="border-radius: 15px;" id="comprovativo-nota">
                    <div class="card-header bg-primary text-white p-3" style="border-radius: 15px 15px 0 0;">
                        <h4 class="mb-0"><i class="fa fa-file-alt me-2"></i> Resultado da Consulta</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <th>Nome do Candidato</th>
                                        <td><?= htmlspecialchars($dados['nome']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Curso</th>
                                        <td><?= htmlspecialchars($dados['curso']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nota</th>
                                        <td>
                                            <?php
                                            if ($dados['nota'] === null || $dados['nota'] === '') {
                                                echo "<span class='text-muted'>Nota ainda não lançada</span>";
                                            } else {
                                                $nota = floatval($dados['nota']);
                                                if ($nota >= 10) {
                                                    echo "<span class='nota-azul'>" . htmlspecialchars($dados['nota']) . "</span>";
                                                } else {
                                                    echo "<span class='nota-vermelha'>" . htmlspecialchars($dados['nota']) . "</span>";
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Resultado de Aptidão</th>
                                        <td>
                                            <?php
                                            $apto = false;
                                            if ($dados['nota'] === null || $dados['nota'] === '') {
                                                echo "<span class='text-muted'>Aguardando lançamento da nota</span>";
                                            } else {
                                                // Exibe o campo obs, se existir, senão calcula apto/não apto pela nota
                                                if (!empty($dados['resultado_aptidao'])) {
                                                    echo htmlspecialchars($dados['resultado_aptidao']);
                                                    // Se o campo obs indicar "Apto" (case-insensitive), também considera apto
                                                    if (strtolower(trim($dados['resultado_aptidao'])) === 'apto') {
                                                        $apto = true;
                                                    }
                                                } else {
                                                    $nota = floatval($dados['nota']);
                                                    if ($nota >= 10) {
                                                        echo "<span class='text-success fw-bold'>Apto</span>";
                                                        $apto = true;
                                                    } else {
                                                        echo "<span class='text-danger fw-bold'>Não Apto</span>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bilhete de Identidade</th>
                                        <td><?= htmlspecialchars($dados['bi']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        // Exibe o link de matrícula se o candidato estiver apto e ainda não fez matrícula
                        if (isset($apto) && $apto) {
                            echo '<div class="text-center">';
                            if (isset($ja_matriculado) && $ja_matriculado) {
                                echo '<span class="msg-matricula-realizada"><i class="fa fa-check-circle me-2"></i>Este candidato já fez matrícula.</span>';
                            } else {
                                echo '<a href="matricula.php?id=' . urlencode($dados['idAluno']) . '" class="btn-matricula"><i class="fa fa-check-circle me-2"></i>Fazer Matrícula</a>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <div class="card-footer bg-light text-center py-3" style="border-radius: 0 0 15px 15px;">
                        <a href="impressao/gerar_comprovativo_nota?id=<?= urlencode($dados['idAluno']); ?>" target="_blank" class="btn btn-primary btn-lg px-5">
                            <i class="fa fa-print me-2"></i> Imprimir Comprovativo
                        </a>
                    </div>
                </div>
            <?php elseif (isset($mensagem)): ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        title: 'Atenção!',
                        text: '<?= addslashes($mensagem) ?>',
                        icon: 'warning',
                        confirmButtonText: 'Tentar Novamente'
                    });
                </script>
            <?php endif; ?>
        </div>

    </div>
    <br><br><br><br><br>
</div>
<!-- Fim da Consulta de Nota e Resultado de Aptidão -->

<?php
include_once "footer.php";
?>
<script>
// Remover função imprimirComprovativo pois agora é feito via mPDF
</script>
</body>
</html>
