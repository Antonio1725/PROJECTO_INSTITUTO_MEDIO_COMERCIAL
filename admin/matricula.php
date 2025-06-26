<?php
// Garante que a sessão seja iniciada no início do script, antes de qualquer saída HTML.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
if (!isset($_SESSION["nome_completo"])) {
    header("Location: login.php");
    exit(); // Garante que o script pare de ser executado após o redirecionamento.
}

// Inclui a conexão com o banco de dados
require_once "models/conexao.php";

// Busca a lista de cursos para o filtro, tornando o formulário dinâmico
$cursos = [];
$sql_cursos = "SELECT nome FROM cursos ORDER BY nome ASC";
$result_cursos = $conexao->query($sql_cursos);
if ($result_cursos && $result_cursos->num_rows > 0) {
    while ($row = $result_cursos->fetch_assoc()) {
        $cursos[] = $row['nome'];
    }
}

// --- Lógica de Filtragem ---
$whereClauses = [];
$params = [];
$types = '';

// Filtro por curso
$curso_filtro = $_GET['curso'] ?? '';
if ($curso_filtro && $curso_filtro !== '%') {
    $whereClauses[] = "a.curso_1_opcao = ?";
    $params[] = $curso_filtro;
    $types .= 's';
}

// Filtro por nome
$pesquisa_nome = $_GET['pesquisa'] ?? '';
if ($pesquisa_nome) {
    $whereClauses[] = "a.nome LIKE ?";
    $like_pesquisa = '%' . $pesquisa_nome . '%';
    $params[] = $like_pesquisa;
    $types .= 's';
}

// Monta a cláusula WHERE
$where = !empty($whereClauses) ? implode(' AND ', $whereClauses) : '1';

// --- Consulta ao Banco de Dados ---
$sql = "SELECT a.*, 
        m.data_envio as data_matricula,
        m.foto, m.doc_certificado, m.doc_bi, m.atestado_medico
        FROM aluno_inscricao a 
        INNER JOIN matricula m ON a.id = m.id_inscricao 
        WHERE $where 
        ORDER BY a.nome ASC";

$stmt = $conexao->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();
$total_alunos = $resultado->num_rows;

// Inclui o cabeçalho da página HTML
include_once "header.php";
?>

<div class="d-flex">
    <?php include_once "menulateral.php"; ?>
    <div class="flex-grow-1">
        <?php include_once "container_pricipal.php"; ?>
        
        <!-- Conteúdo principal -->
        <main class="p-4 bg-light">
            <div class="container-fluid">
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-white shadow-sm p-3 rounded-3 mb-4">
                        <li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home" aria-hidden="true"></i> Painel</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Alunos Matriculados</li>
                    </ol>
                </nav>

                <!-- Card de Filtros -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-filter me-2"></i>Filtros de Pesquisa</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="filtroForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label for="cursosSelect" class="form-label">Filtrar por Curso:</label>
                                    <select name="curso" id="cursosSelect" class="form-select" onchange="this.form.submit()">
                                        <option value="%" <?php if (empty($curso_filtro) || $curso_filtro == '%') echo 'selected'; ?>>Todos os Cursos</option>
                                        <?php foreach ($cursos as $curso_item): ?>
                                            <option value="<?php echo htmlspecialchars($curso_item); ?>" <?php if ($curso_filtro == $curso_item) echo 'selected'; ?>>
                                                <?php echo htmlspecialchars($curso_item); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="pesquisarInput" class="form-label">Pesquisar por Nome:</label>
                                    <input type="search" name="pesquisa" placeholder="Digite o nome do aluno" id="pesquisarInput" class="form-control" value="<?php echo htmlspecialchars($pesquisa_nome); ?>">
                                </div>
                                <div class="col-md-3 d-flex">
                                    <button type="submit" class="btn btn-primary flex-grow-1 me-2">
                                        <i class="fa fa-search"></i> Filtrar
                                    </button>
                                    <button type="button" onclick="imprimirLista(<?php echo $total_alunos; ?>)" class="btn btn-success flex-grow-1">
                                        <i class="fa fa-print"></i> Imprimir
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card da Tabela de Resultados -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fa fa-users me-2"></i>Lista de Alunos Matriculados</h5>
                        <span class="badge bg-primary rounded-pill"><?php echo $total_alunos; ?> Encontrados</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="conteudo-impressao">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>B.I</th>
                                        <th>Nascimento</th>
                                        <th>Gênero</th>
                                        <th>1ª Opção</th>
                                        <th>2ª Opção</th>
                                        <th>Nota</th>
                                        <th>Status</th>
                                        <th>Data Matrícula</th>
                                        <th class="text-center">Documentos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($total_alunos > 0): ?>
                                        <?php while($aluno = $resultado->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                                                <td><?php echo htmlspecialchars($aluno['BI']); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($aluno['data_nascimento'])); ?></td>
                                                <td><?php echo $aluno['genero'] == 'M' ? 'Masculino' : 'Feminino'; ?></td>
                                                <td><?php echo htmlspecialchars($aluno['curso_1_opcao']); ?></td>
                                                <td><?php echo htmlspecialchars($aluno['curso_2_opcao']); ?></td>
                                                <td><?php echo number_format($aluno['nota'], 2, ',', '.'); ?></td>
                                                <td>
                                                    <?php $status_class = $aluno['obs'] == 'Apto' ? 'bg-success' : 'bg-danger'; ?>
                                                    <span class="badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($aluno['obs']); ?></span>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($aluno['data_matricula'])); ?></td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-file-text-o me-1"></i> Ver Docs
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="../uploads/<?php echo htmlspecialchars($aluno['foto']); ?>" target="_blank"><i class="fa fa-picture-o fa-fw me-2"></i>Foto</a></li>
                                                            <li><a class="dropdown-item" href="../uploads/<?php echo htmlspecialchars($aluno['doc_certificado']); ?>" target="_blank"><i class="fa fa-certificate fa-fw me-2"></i>Certificado</a></li>
                                                            <li><a class="dropdown-item" href="../uploads/<?php echo htmlspecialchars($aluno['doc_bi']); ?>" target="_blank"><i class="fa fa-id-card-o fa-fw me-2"></i>B.I</a></li>
                                                            <li><a class="dropdown-item" href="../uploads/<?php echo htmlspecialchars($aluno['atestado_medico']); ?>" target="_blank"><i class="fa fa-medkit fa-fw me-2"></i>Atestado</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center p-4">
                                                Nenhum aluno matriculado encontrado com os filtros aplicados.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Custom JS -->
<script src="js/custom.js"></script>

<script>
function imprimirLista(totalAlunos) {
    const conteudoEl = document.getElementById('conteudo-impressao');
    // Clona o nó para evitar modificar a página ao vivo
    const clonedConteudo = conteudoEl.cloneNode(true);

    // Substitui os dropdowns por links simples para uma visualização amigável para impressão
    clonedConteudo.querySelectorAll('.dropdown').forEach(dropdown => {
        const links = [];
        dropdown.querySelectorAll('.dropdown-menu a').forEach(a => {
            links.push(`<a href="${a.href}" target="_blank">${a.textContent.trim()}</a>`);
        });
        const cell = dropdown.closest('td');
        if (cell) {
            cell.innerHTML = links.join('<br>');
        }
    });

    const conteudoParaImpressao = clonedConteudo.innerHTML;
    const janela = window.open('', '', 'width=1200,height=800');
    
    const cursoSelecionadoEl = document.querySelector('#cursosSelect');
    const cursoSelecionadoTexto = cursoSelecionadoEl.options[cursoSelecionadoEl.selectedIndex].text;
    const cursoFiltro = cursoSelecionadoEl.value !== '%' ? `
        <div style="text-align: center; margin-bottom: 20px;">
            <strong>Curso: </strong> ${cursoSelecionadoTexto}
        </div>` : '';

    const estatisticas = `
        <div class="estatisticas">
            <strong>Total de Alunos na Lista:</strong> ${totalAlunos}
        </div>`;

    janela.document.write('<html><head>');
    janela.document.write('<title>Lista de Alunos Matriculados</title>');
    janela.document.write('<style>');
    janela.document.write(`
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 11px; }
        th, td { border: 1px solid #dee2e6; padding: 8px; text-align: left; word-wrap: break-word; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .cabecalho { text-align: center; margin-bottom: 30px; }
        .cabecalho h1 { font-size: 22px; margin-bottom: 5px; }
        .cabecalho h2 { font-size: 18px; margin: 0; font-weight: normal; }
        .estatisticas { 
            margin: 20px 0; padding: 10px; background-color: #f8f9fa;
            border-radius: 5px; border: 1px solid #dee2e6; font-size: 14px;
        }
        .badge { display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
        .bg-success { background-color: #198754 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        a { color: #0d6efd; text-decoration: none; }
        @media print {
            @page { size: A4 landscape; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .btn, .dropdown-toggle { display: none !important; }
            a { color: #000 !important; text-decoration: none; }
        }
    `);
    janela.document.write('</style>');
    janela.document.write('</head><body>');
    
    janela.document.write('<div class="cabecalho">');
    janela.document.write('<h1>Lista de Alunos Matriculados</h1>');
    janela.document.write('<h2>Instituto Médio Comercial de Luanda</h2>');
    janela.document.write('</div>');
    
    janela.document.write(cursoFiltro);
    janela.document.write(estatisticas);
    
    janela.document.write(conteudoParaImpressao);
    janela.document.write('</body></html>');
    janela.document.close();
    
    setTimeout(() => {
        janela.focus();
        janela.print();
    }, 500);
}
</script>

</body>
</html>
