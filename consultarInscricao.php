<?php
include_once "admin/models/conexao.php";
$dados = null;
$erro = null;
if (isset($_POST['consultar'])) {
    $bi = $conexao->real_escape_string($_POST['bi']);
    if (!preg_match('/^\d{9}[A-Z]{2}\d{3}$/', $bi)) {
        $erro = 'O número do BI deve ser no formato 007817482LA040.';
    } else {
        $sql = "SELECT * FROM aluno_inscricao WHERE bi = '$bi' LIMIT 1";
        $res = $conexao->query($sql);
        if ($res && $res->num_rows > 0) {
            $dados = $res->fetch_assoc();
        } else {
            $erro = 'Nenhuma candidatura encontrada para este número de BI.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<?php
    include_once "head.php";
  
    ?>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php

include_once "menu_principal.php";

include_once "admin/models/conexao.php"
?>
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm p-4 mb-5" style="border-radius: 15px;">
                <div class="card-body">
                    <h2 class="text-center mb-3 fw-bold text-primary">Consultar Candidatura</h2>
                    <p class="text-center text-muted mb-4">Para verificar o estado da sua candidatura, por favor, insira o número do seu Bilhete de Identidade abaixo.</p>
                    <form method="post" class="needs-validation" novalidate>
                        <div class="input-group input-group-lg mb-3">
                            <span class="input-group-text bg-light border-end-0" id="bi-addon"><i class="fa fa-id-card text-primary"></i></span>
                            <input type="text" class="form-control" id="bi" name="bi" placeholder="Ex: 007817482LA040" required pattern="^\d{9}[A-Z]{2}\d{3}$" title="Insira um BI válido: 9 números, 2 letras maiúsculas e 3 números (ex: 007817482LA040)" aria-describedby="bi-addon">
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="consultar" class="btn btn-primary btn-lg"><i class="fa fa-search me-2"></i>Consultar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($erro): ?>
        <script>Swal.fire({title: 'Atenção!', text: '<?= addslashes($erro) ?>', icon: 'warning', confirmButtonText: 'Tentar Novamente'});</script>
    <?php elseif ($dados): ?>
        <div class="card shadow-lg border-0" style="border-radius: 15px;">
            <div class="card-header bg-primary text-white p-3" style="border-radius: 15px 15px 0 0;">
                <h4 class="mb-0"><i class="fa fa-user-circle me-2"></i> Dados da Candidatura</h4>
            </div>
            <div class="card-body p-4">
                
                <!-- Dados Pessoais -->
                <h5 class="mb-3 mt-2 border-bottom pb-2 text-secondary">Dados Pessoais</h5>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label text-muted small">Nome Completo</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['nome']) ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Data de Nascimento</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars(date('d/m/Y', strtotime($dados['data_nascimento']))) ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Nº do B.I.</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['bi']) ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Gênero</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['genero'] == 'M' ? 'Masculino' : 'Feminino') ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Nacionalidade</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['nacionalidade']) ?></p>
                    </div>
                     <div class="col-md-12">
                        <label class="form-label text-muted small">Email</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['email'] ?: 'Não informado') ?></p>
                    </div>
                </div>

                <!-- Morada e Naturalidade -->
                <h5 class="mb-3 mt-4 border-bottom pb-2 text-secondary">Morada e Naturalidade</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Natural de</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['natural_de']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Província</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['provincia']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Município</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['municipio']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Morada</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['morada']) ?></p>
                    </div>
                </div>

                <!-- Filiação e Contatos -->
                <h5 class="mb-3 mt-4 border-bottom pb-2 text-secondary">Filiação e Contatos</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nome da Mãe</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['nome_mae']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nome do Pai</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['nome_pai']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nome do Encarregado</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['nome_encarregado']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Telefone</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['terminal']) ?></p>
                    </div>
                </div>

                <!-- Opções de Curso -->
                <h5 class="mb-3 mt-4 border-bottom pb-2 text-secondary">Opções de Curso</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">1ª Opção de Curso</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['curso_1_opcao']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">2ª Opção de Curso</label>
                        <p class="form-control-plaintext bg-light p-2 rounded mb-0"><?= htmlspecialchars($dados['curso_2_opcao']) ?></p>
                    </div>
                </div>
            </div>
            <?php if (isset($dados['id'])): ?>
            <div class="card-footer bg-light text-center py-4" style="border-radius: 0 0 15px 15px;">
                <a href="impressao/gerar_recibo.php?id=<?= urlencode($dados['id']) ?>" target="_blank" class="btn btn-primary btn-lg px-5">
                    <i class="fa fa-print me-2"></i> Imprimir Recibo de Inscrição
                </a>
            </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php
include_once "footer.php";

?>
</body>
</html>


