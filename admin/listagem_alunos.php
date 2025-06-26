<?php
// Garante que a sessão seja iniciada no início do script.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
if (!isset($_SESSION["nome_completo"])) {
    header("Location: login.php");
    exit();
}

// Inclui os arquivos necessários.
require_once "models/conexao.php";
require_once "header.php";

// Otimização para buscar todas as contagens em uma única consulta.
$sql_contagens = "
    SELECT 
        (SELECT COUNT(id) FROM matricula) as total_matricula,
        (SELECT COUNT(id) FROM aluno_inscricao) as total_inscricao,
        (SELECT COUNT(id) FROM noticias WHERE stato='Ativo') as total_noticias,
        (SELECT COUNT(id) FROM eventos WHERE stato='Ativo') as total_eventos,
        (SELECT COUNT(id) FROM cursos) as total_cursos
";
$resultado_contagens = $conexao->query($sql_contagens);
$contagens = $resultado_contagens ? $resultado_contagens->fetch_assoc() : [];

// Atribui os valores usando o operador de coalescência nula.
$numLinhaMatricula = $contagens['total_matricula'] ?? 0;
$numLinhaAEstudante = $contagens['total_inscricao'] ?? 0;
$numLinhaNoticias = $contagens['total_noticias'] ?? 0;
$numEventos = $contagens['total_eventos'] ?? 0;
$numLinhaCursos = $contagens['total_cursos'] ?? 0;

// Busca os cursos para o dropdown de filtro.
$cursos_sql = "SELECT nome FROM cursos ORDER BY nome ASC";
$cursos_resultado = $conexao->query($cursos_sql);
$cursos = [];
if ($cursos_resultado) {
    while ($row = $cursos_resultado->fetch_assoc()) {
        $cursos[] = $row['nome'];
    }
}
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
                    <ol class="breadcrumb bg-white shadow-sm p-3 rounded-3">
                        <li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home" aria-hidden="true"></i> Painel</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Listagem de Alunos Inscritos</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0 fw-bold text-primary"><i class="fa fa-users me-2" aria-hidden="true"></i>Alunos Inscritos</h4>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="p-3 mb-4 bg-light rounded-3 border">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <label for="curso1" class="form-label fw-bold">Filtrar por Curso</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-graduation-cap" aria-hidden="true"></i></span>
                                        <select name="curso1" id="curso1" class="form-select">
                                            <option value="%">Todos os Cursos</option>
                                            <?php foreach ($cursos as $curso): ?>
                                                <option value="<?= htmlspecialchars($curso) ?>"><?= htmlspecialchars($curso) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label for="pesquisar" class="form-label fw-bold">Pesquisar por Nome</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></span>
                                        <input type="search" placeholder="Digite o nome do aluno..." id="pesquisar" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <a href="impressao/listarAlunos.php" target="_blank" id="imprimir" class="btn btn-success w-100">
                                        <i class="fa fa-print me-2"></i> Imprimir Lista
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered align-middle">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>Nome</th>
                                        <th>B.I</th>
                                        <th>Data Nasc.</th>
                                        <th>Gênero</th>
                                        <th>1ª Opção</th>
                                        <th>2ª Opção</th>
                                        <th>Nota</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="resultado">
                                    <tr>
                                        <td colspan="9" class="text-center p-5">
                                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                                <span class="visually-hidden">Carregando...</span>
                                            </div>
                                            <p class="mt-2 mb-0 text-muted">Aguarde, carregando alunos...</p>
                                        </td>
                                    </tr>
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
<!-- Custom JS (se houver scripts globais) -->
<script src="js/custom.js"></script>






<!-- Modal para Inserir/Editar Nota -->
<div class="modal fade" id="modalNota" tabindex="-1" aria-labelledby="modalNotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <form id="formNota" novalidate>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalNotaLabel"><i class="fa fa-pencil-square-o me-2" aria-hidden="true"></i>Inserir Nota do Aluno</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="aluno_id" id="alunoId">
                    <div class="mb-3">
                        <label for="nota" class="form-label fw-bold">Nota (0 a 20)</label>
                        <div class="input-group">
                             <span class="input-group-text"><i class="fa fa-star" aria-hidden="true"></i></span>
                            <input type="number" name="nota" id="nota" class="form-control form-control-lg" required min="0" max="20" step="0.01" placeholder="Ex: 15.5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times me-2"></i>Cancelar</button>
                    <button type="submit" id="salvar" class="btn btn-primary"><i class="fa fa-save me-2"></i>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
// Funções globais para serem acessadas pelo HTML dinâmico, que é carregado via fetch.
function buscarNota(id) {
    document.getElementById('alunoId').value = id;
    document.getElementById('nota').value = ''; // Limpa a nota anterior
   
}

document.addEventListener('DOMContentLoaded', function() {
    const cursoSelect = document.getElementById('curso1');
    const pesquisarInput = document.getElementById('pesquisar');
    const resultadoTbody = document.getElementById('resultado');
    const imprimirLink = document.getElementById('imprimir');
    const formNota = document.getElementById('formNota');
    const notaModalEl = document.getElementById('modalNota');
    const notaModal = new bootstrap.Modal(notaModalEl);

    let debounceTimer;

    // Função para evitar múltiplas requisições em um curto intervalo de tempo (debounce)
    const debounce = (func, delay) => {
        return (...args) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                func.apply(this, args);
            }, delay);
        };
    };

    // Função para buscar e exibir os dados dos alunos usando a API Fetch
    const buscar = async () => {
        const curso1 = cursoSelect.value.trim();
        const pesquisar = pesquisarInput.value.trim();
        const params = new URLSearchParams({ curso1, pesquisar });

        resultadoTbody.innerHTML = `<tr><td colspan="9" class="text-center p-5"><div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"><span class="visually-hidden">Carregando...</span></div><p class="mt-2 mb-0 text-muted">Aguarde, carregando alunos...</p></td></tr>`;

        try {
            const response = await fetch("control/listarAlunos.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: params.toString()
            });

            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }

            const dados = await response.text();
            resultadoTbody.innerHTML = dados;
            imprimirLink.href = `impressao/listarAlunos.php?${params.toString()}`;
        } catch (error) {
            resultadoTbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger p-4"><strong>Erro ao buscar dados:</strong> ${error.message}</td></tr>`;
            console.error("Erro na requisição:", error);
        }
    };

    // Função para salvar a nota do aluno
    const salvarNota = async (event) => {
        event.preventDefault();
        const notaInput = document.getElementById('nota');
        const nota = parseFloat(notaInput.value);

        if (isNaN(nota) || nota < 0 || nota > 20) {
            alert('Por favor, insira uma nota válida entre 0 e 20.');
            notaInput.focus();
            return;
        }

        const salvarBtn = document.getElementById('salvar');
        salvarBtn.disabled = true;
        salvarBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';

        try {
            const formData = new FormData(formNota);
            const response = await fetch('salvar_nota.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Falha ao salvar a nota. Verifique a resposta do servidor.');
            }
            
            notaModal.hide();
            await buscar(); // Atualiza a lista de alunos

        } catch (error) {
            console.error('Erro ao salvar a nota:', error);
            alert(`Erro ao salvar a nota: ${error.message}`);
        } finally {
            salvarBtn.disabled = false;
            salvarBtn.innerHTML = '<i class="fa fa-save me-2"></i>Salvar';
        }
    };

    // Adiciona os event listeners para os filtros e o formulário do modal
    cursoSelect.addEventListener('change', buscar);
    pesquisarInput.addEventListener('keyup', debounce(buscar, 300));
    formNota.addEventListener('submit', salvarNota);

    // Carrega os dados iniciais ao carregar a página
    buscar();
});
</script>

</body>
</html>
