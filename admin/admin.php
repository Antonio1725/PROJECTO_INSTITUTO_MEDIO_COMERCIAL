<?php
// Garante que a sessão seja iniciada no início do script, antes de qualquer saída HTML.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
// Esta verificação é crucial e deve ser feita antes de carregar o restante da página.
if (!isset($_SESSION["nome_completo"])) {
    // O redirecionamento via header do PHP é mais eficiente e seguro.
    header("Location: login.php");
    exit(); // Garante que o script pare de ser executado após o redirecionamento.
}

// Como a verificação de login já foi feita, podemos carregar os outros arquivos.
include_once "models/conexao.php";
include_once "header.php";

// Otimização para buscar todas as contagens em uma única e robusta consulta
$sql = "
    SELECT 
        (SELECT COUNT(id) FROM matricula) as total_matricula,
        (SELECT COUNT(id) FROM aluno_inscricao) as total_inscricao,
        (SELECT COUNT(id) FROM noticias WHERE stato='Ativo') as total_noticias,
        (SELECT COUNT(id) FROM eventos WHERE stato='Ativo') as total_eventos
";

$resultado = $conexao->query($sql);
// Garante que $contagens seja um array, mesmo que a consulta falhe.
$contagens = $resultado ? $resultado->fetch_assoc() : [];

// Atribui os valores usando o operador de coalescência nula para definir 0 em caso de falha.
$numLinhaMatricula = $contagens['total_matricula'] ?? 0;
$numLinhaAEstudante = $contagens['total_inscricao'] ?? 0;
$numLinhaNoticias = $contagens['total_noticias'] ?? 0;
$numEventos = $contagens['total_eventos'] ?? 0;
$numLinhaCursos = $contagens['total_cursos'] ?? 0;

?>

<div class="d-flex">
    <?php include_once "menulateral.php"; ?>
    <div class="flex-grow-1">
        <?php include_once "container_pricipal.php"; ?>
        
        <!-- Conteúdo principal -->
        <main class="p-4" style="background-color: #f8f9fa;">
            <div class="container-fluid">
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin"><i class="fa fa-home" aria-hidden="true"></i> Início</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Painel</li>
                    </ol>
                </nav>
                <!-- Cartões de Resumo -->
                <div class="row g-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="card text-white" style="background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Alunos Inscritos</h5>
                                        <h2 class="fw-bold"><?php echo $numLinhaAEstudante; ?></h2>
                                    </div>
                                    <i class="fa fa-user-plus fa-3x opacity-50"></i>
                                </div>
                                <a href="listagem_alunos" class="text-white stretched-link mt-2 d-block">Saber mais <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Alunos Matriculados</h5>
                                        <h2 class="fw-bold"><?php echo  $numLinhaMatricula;?></h2>
                                    </div>
                                    <i class="fa fa-graduation-cap fa-3x opacity-50"></i>
                                </div>
                                <a href="matricula" class="text-white stretched-link mt-2 d-block">Saber mais <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Notícias</h5>
                                        <h2 class="fw-bold"><?php echo  $numLinhaNoticias;?></h2>
                                    </div>
                                    <i class="fa fa-newspaper-o fa-3x opacity-50"></i>
                                </div>
                                <a href="admin-noticia-all" class="text-white stretched-link mt-2 d-block">Saber mais <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Eventos</h5>
                                        <h2 class="fw-bold"><?php echo  $numEventos;?></h2>
                                    </div>
                                    <i class="fa fa-calendar fa-3x opacity-50"></i>
                                </div>
                                <a href="admin-event-all" class="text-white stretched-link mt-2 d-block">Saber mais <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // --- Dados para o gráfico de inscrições por curso ---
                $sqlCursosInscritos = "SELECT curso_1_opcao, COUNT(*) as total FROM aluno_inscricao GROUP BY curso_1_opcao ORDER BY total DESC";
                $queryCursosInscritos = $conexao->query($sqlCursosInscritos);

                $cursosLabels = [];
                $cursosData = [];
                if ($queryCursosInscritos) {
                    while ($dados = mysqli_fetch_assoc($queryCursosInscritos)) {
                        $cursosLabels[] = $dados['curso_1_opcao'];
                        $cursosData[] = $dados['total'];
                    }
                }

                // --- Dados para o gráfico de distribuição por gênero ---
                $sqlGenero = "SELECT genero, COUNT(*) as total FROM aluno_inscricao GROUP BY genero";
                $queryGenero = $conexao->query($sqlGenero);

                $generoLabels = [];
                $generoData = [];
                if ($queryGenero) {
                    while ($dados = mysqli_fetch_assoc($queryGenero)) {
                        $generoLabels[] = $dados['genero'];
                        $generoData[] = $dados['total'];
                    }
                }
                ?>

                <!-- Gráficos de Análise -->
                <div class="row g-4 mt-4">
                    <!-- Gráfico de Inscrições por Curso -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">Inscrições por Curso</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="cursosChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico de Distribuição por Gênero -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">Distribuição por Gênero</h5>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div style="position: relative; height:300px; width:300px">
                                    <canvas id="generoChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart.js -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // Gráfico de Barras: Inscrições por Curso
                        const ctxCursos = document.getElementById('cursosChart');
                        if (ctxCursos) {
                            new Chart(ctxCursos, {
                                type: 'bar',
                                data: {
                                    labels: <?php echo json_encode($cursosLabels); ?>,
                                    datasets: [{
                                        label: 'Nº de Inscritos',
                                        data: <?php echo json_encode($cursosData); ?>,
                                        backgroundColor: 'rgba(30, 64, 175, 0.8)',
                                        borderColor: 'rgba(30, 64, 175, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                // Garante que o eixo Y mostre apenas números inteiros
                                                callback: function(value) {if (value % 1 === 0) {return value;}}
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        title: {
                                            display: true,
                                            text: 'Cursos mais procurados'
                                        }
                                    }
                                }
                            });
                        }

                        // Gráfico de Pizza: Distribuição por Gênero
                        const ctxGenero = document.getElementById('generoChart');
                        if (ctxGenero) {
                            new Chart(ctxGenero, {
                                type: 'pie',
                                data: {
                                    labels: <?php echo json_encode($generoLabels); ?>,
                                    datasets: [{
                                        data: <?php echo json_encode($generoData); ?>,
                                        backgroundColor: [
                                            'rgba(54, 162, 235, 0.8)', // Azul para Masculino
                                            'rgba(255, 99, 132, 0.8)', // Rosa para Feminino
                                            'rgba(255, 206, 86, 0.8)'  // Amarelo para Outro
                                        ],
                                        borderColor: [
                                            '#FFFFFF'
                                        ],
                                        borderWidth: 2
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        },
                                        title: {
                                            display: true,
                                            text: 'Relação de Gênero dos Inscritos'
                                        }
                                    }
                                }
                            });
                        }
                    });
                </script>
                <!-- Tabelas de Detalhes -->
                <div class="row g-4 mt-4">
                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Últimos Cursos Adicionados (Total: <?php echo $numLinhaCursos; ?>)</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Visão geral dos últimos cursos oferecidos.</p>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Curso</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php
                                                $sqlCursos = "SELECT nome, estatus FROM cursos ORDER BY id DESC LIMIT 6";
                                                $queryCursos = $conexao->query($sqlCursos);
                                                while ($dadosCurso = mysqli_fetch_assoc($queryCursos)) {
                                                    $nomeCurso = htmlspecialchars($dadosCurso['nome']);
                                                    $statusCurso = htmlspecialchars($dadosCurso['estatus']);
                                                    $badge_class = ($statusCurso == 'Ativo') ? 'bg-success' : 'bg-secondary';
                                                    echo "<tr><td>$nomeCurso</td><td><span class='badge $badge_class'>$statusCurso</span></td></tr>";
                                                }
                                           ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Últimos Alunos Inscritos</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Lista dos últimos 6 alunos que se inscreveram.</p>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Curso (1ª Opção)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT nome, curso_1_opcao FROM aluno_inscricao ORDER BY id DESC LIMIT 6";
                                        $query = $conexao->query($sql);
                                        while ($dados = mysqli_fetch_assoc($query)){
                                            $nome = htmlspecialchars($dados['nome']);
                                            $curso = htmlspecialchars($dados['curso_1_opcao']);
                                            echo "<tr><td>$nome</td><td>$curso</td></tr>";
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
        </main>
    </div>
</div>

<!-- O bloco if/else foi removido daqui, pois a verificação de login agora é feita no topo do script. -->
<!-- Se o usuário não estiver logado, ele já foi redirecionado. -->


<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Custom JS -->
<script src="js/custom.js"></script>

</body>
</html>
