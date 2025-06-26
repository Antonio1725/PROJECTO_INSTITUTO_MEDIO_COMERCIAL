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
    </style>
</head>

<body>
<?php
include_once "menu_principal.php";
include_once "admin/models/conexao.php";
?>

<!-- Team Start -->
<div class="container-xxl py-5">
    <div class="container">

        <?php
        $conn = $conexao;

        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtém o número do B.I do formulário
            $bi = trim($_POST['bi']);

            // Prepara a consulta SQL para buscar todos os exames do aluno
            // Corrigido para refletir a estrutura da tabela 'exames'
            // e removido campos inexistentes como 'duracao' e 'periodo'
            $sql = "SELECT 
                        aluno_inscricao.id as idAluno,
                        exames.id as id, 
                        exames.nome as nome, 
                        exames.curso as curso, 
                        exames.dataInicio as dataInicio, 
                        exames.dataFim as dataFim, 
                        exames.data as data1, 
                        exames.sala as sala, 
                        exames.stato as stato
                    FROM exames 
                    INNER JOIN aluno_inscricao ON exames.id = aluno_inscricao.idExame 
                    WHERE aluno_inscricao.BI = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $bi);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $exames = [];
                while ($row = $result->fetch_assoc()) {
                    $exames[] = $row;
                }
            } else {
                $mensagem = "Nenhum exame encontrado para o B.I informado.";
            }

            $stmt->close();
        }
        ?>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm p-4 mb-5" style="border-radius: 15px;">
                        <div class="card-body">
                            <h2 class="text-center mb-3 fw-bold text-primary">Consultar Dados do Exame</h2>
                            <p class="text-center text-muted mb-4">Para verificar os detalhes do seu exame de admissão, por favor, insira o número do seu Bilhete de Identidade abaixo.</p>
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

            <?php if (isset($exames) && count($exames) > 0): ?>
                <div class="card shadow-lg border-0" style="border-radius: 15px;" id="comprovativo-exame">
                    <div class="card-header bg-primary text-white p-3" style="border-radius: 15px 15px 0 0;">
                        <h4 class="mb-0"><i class="fa fa-file-alt me-2"></i> Resultado da Consulta</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                <tr>
                                    <th>Nome do Exame</th>
                                    <th>Curso</th>
                                    <th>Data</th>
                                    <th>Início</th>
                                    <th>Fim</th>
                                    <th>Sala</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($exames as $exame): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($exame['nome']); ?></td>
                                        <td><?= htmlspecialchars($exame['curso']); ?></td>
                                        <td>
                                            <?php
                                            if (!empty($exame['data1']) && $exame['data1'] !== '0000-00-00') {
                                                echo htmlspecialchars(date('d/m/Y', strtotime($exame['data1'])));
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (!empty($exame['dataInicio'])) {
                                                echo htmlspecialchars(date('H:i', strtotime($exame['dataInicio'])));
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (!empty($exame['dataFim'])) {
                                                echo htmlspecialchars(date('H:i', strtotime($exame['dataFim'])));
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($exame['sala']); ?></td>
                                        <td>
                                            <?php
                                            $status = htmlspecialchars($exame['stato']);
                                            $badge_class = 'bg-secondary';
                                            if ($status === 'Ativo') {
                                                $badge_class = 'bg-success';
                                            } elseif ($status === 'Inativo') {
                                                $badge_class = 'bg-danger';
                                            } elseif ($status === 'Excluir') {
                                                $badge_class = 'bg-warning text-dark';
                                            }
                                            ?>
                                            <span class="badge <?= $badge_class; ?>"><?= $status; ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-center py-3" style="border-radius: 0 0 15px 15px;">
                        <a href="impressao/gerar_comprovativo_exame.php?id=<?= urlencode($exames[0]['idAluno']); ?>" target="_blank" class="btn btn-primary btn-lg px-5">
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
<!-- Team End -->

<?php
include_once "footer.php";
?>
<script>
// Remover função imprimirComprovativo pois agora é feito via mPDF
</script>
</body>
</html>
