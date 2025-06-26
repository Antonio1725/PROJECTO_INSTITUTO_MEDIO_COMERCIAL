<?php
// Início do buffer de saída para evitar problemas de header
ob_start();
session_start();

include_once "admin/models/conexao.php";
include_once "head.php";
include_once "menu_principal.php";

// Função para upload seguro de arquivos
function uploadFileSecure($file, $uploadDir, $allowedExtensions) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return "Falha ao fazer upload do arquivo.";
    }
    $fileInfo = pathinfo($file['name']);
    $ext = strtolower($fileInfo['extension'] ?? '');
    if (!in_array($ext, $allowedExtensions)) {
        return "Tipo de arquivo não permitido: .$ext";
    }
    if ($file['size'] > 1 * 1024 * 1024) {
        return "Arquivo muito grande: " . $file['name'];
    }
    $safeName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileInfo['filename']) . '.' . $ext;
    $dest = rtrim($uploadDir, '/') . '/' . $safeName;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return "Falha ao mover o arquivo: " . $file['name'];
    }
    return $safeName;
}

// Inicialização de variáveis
$id_inscricao = isset($_GET['id']) ? intval($_GET['id']) : (isset($_GET['id_matricula']) ? intval($_GET['id_matricula']) : 0);
$msg = '';
$msg_class = '';




// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
  
    $id_inscricao = intval($_POST['id_inscricao']);
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $foto = uploadFileSecure($_FILES['foto'], $uploadDir, ['jpg','jpeg','png']);
    $doc_certificado = uploadFileSecure($_FILES['doc_certificado'], $uploadDir, ['pdf']);
    $doc_bi = uploadFileSecure($_FILES['doc_bi'], $uploadDir, ['pdf']);
    $atestado_medico = uploadFileSecure($_FILES['atestado_medico'], $uploadDir, ['pdf']);
   
    $erros = [];
   


    // DEBUG: Verifique se a conexão está OK
    if (true) {
      
       
    echo "<script>alert('Teste125');</script>";
        // Verifique se o id_inscricao é válido
        if ($id_inscricao <= 0) {
            $msg = 'ID de inscrição inválido.';
            $msg_class = 'alert-danger';
        } else {
            // Verifica se já existe matrícula para este id_inscricao
            $sql_check = "SELECT id FROM matricula WHERE id_inscricao = ?";
            
            $stmt_check = $conexao->prepare($sql_check);
            if ($stmt_check) {
                $stmt_check->bind_param("i", $id_inscricao);
                $stmt_check->execute();
                $stmt_check->store_result();
                if ($stmt_check->num_rows > 0) {
                    $msg = 'Já existe uma matrícula cadastrada para este candidato.';
                    $msg_class = 'alert-warning';
                    $stmt_check->close();
                } else {
                    $stmt_check->close();
                    // Tente inserir no banco de dados
                    $sql = "INSERT INTO matricula (id_inscricao, foto, doc_certificado, doc_bi, atestado_medico) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conexao->prepare($sql);
                    if ($stmt === false) {
                        $msg = 'Erro ao preparar statement: ' . $conexao->error;
                        $msg_class = 'alert-danger';
                    } else {
                        // Corrigido: bind_param deve ser "issss" (i para int, s para string)
                        $stmt->bind_param("issss", $id_inscricao, $foto, $doc_certificado, $doc_bi, $atestado_medico);
                        if ($stmt->execute()) {
                            $msg = 'Macricula Feita com sucesso!';
                            $msg_class = 'alert-success';
                        } else {
                            $msg = 'Erro ao cadastrar: ' . $stmt->error;
                            $msg_class = 'alert-danger';
                        }
                        $stmt->close();
                    }
                }
            } else {
                $msg = 'Erro ao preparar verificação: ' . $conexao->error;
                $msg_class = 'alert-danger';
            }
        }
    } else {
        $msg = 'Erro no upload dos arquivos: <br>' . implode('<br>', $erros);
        $msg_class = 'alert-danger';
    }
    // Redireciona para evitar reenvio do formulário
    header("Location: matricula.php?id_matricula=$id_inscricao&msg=" . urlencode($msg) . "&msg_class=" . urlencode($msg_class));
    exit;
}



// Mensagem de feedback
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    $msg_class = $_GET['msg_class'] ?? 'alert-info';
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Matrícula</title>
    <!-- Bootstrap 5 CDN para melhoria visual -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #f0f4f8 0%, #e0e7ef 100%);
            min-height: 100vh;
        }
        .matricula-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(30,64,175,0.10), 0 1.5px 4px rgba(0,0,0,0.04);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 520px;
            margin: 40px auto 0 auto;
            position: relative;
        }
        .matricula-card .form-label {
            font-weight: 600;
            color: #1e40af;
        }
        .matricula-card .form-control {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 1rem;
        }
        .matricula-card .form-control:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 0.2rem rgba(30,64,175,0.10);
        }
        .matricula-card .alert {
            font-size: 0.98rem;
            border-radius: 8px;
        }
        .matricula-card .btn-primary {
            background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
            border: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(30,64,175,0.08);
            transition: background 0.2s;
        }
        .matricula-card .btn-primary:hover {
            background: linear-gradient(90deg, #0ea5e9 60%, #1e40af 100%);
        }
        .matricula-card .file-input {
            padding: 8px 0;
        }
        .matricula-card .file-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .matricula-card .file-label i {
            color: #0ea5e9;
            font-size: 1.2rem;
        }
        .matricula-card .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 1.5rem;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .matricula-card .msg {
            margin-top: 20px;
        }
        @media (max-width: 600px) {
            .matricula-card {
                padding: 1.2rem 0.5rem 1.5rem 0.5rem;
            }
        }
    </style>
    <!-- Ícones do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<div class="container">
    <div class="matricula-card shadow">
        <div class="section-title">
            <i class="bi bi-person-badge"></i> Matrícula de Aluno
        </div>
        <?php if (!empty($msg)): ?>
            <div class="alert <?= htmlspecialchars($msg_class) ?> msg text-center"><?= $msg ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="id_inscricao" id="id_inscricao" value="<?= htmlspecialchars($id_inscricao) ?>">
            <div class="mb-3">
                <label for="foto" class="form-label file-label">
                    <i class="bi bi-image"></i> Foto Tipo Passe
                </label>
                <input type="file" accept=".jpg,.png,.jpeg" class="form-control file-input" id="foto" name="foto" required>
            </div>
            <div class="mb-3">
                <label for="doc_certificado" class="form-label file-label">
                    <i class="bi bi-file-earmark-text"></i> Cópia do Certificado de Habilitação
                </label>
                <input type="file" accept=".pdf" class="form-control file-input" id="doc_certificado" name="doc_certificado" required>
            </div>
            <div class="mb-3">
                <label for="doc_bi" class="form-label file-label">
                    <i class="bi bi-person-vcard"></i> Cópia do BI
                </label>
                <input type="file" accept=".pdf" class="form-control file-input" id="doc_bi" name="doc_bi" required>
            </div>
            <div class="mb-3">
                <label for="atestado_medico" class="form-label file-label">
                    <i class="bi bi-file-medical"></i> Atestado Médico
                </label>
                <input type="file" accept=".pdf" class="form-control file-input" id="atestado_medico" name="atestado_medico" required>
            </div>
            <div class="mb-3">
                <div class='alert alert-warning text-center'>
                    <i class="bi bi-exclamation-triangle"></i>
                    A Foto deve estar em <b>JPG/PNG/JPEG</b> (máx. 1MB).<br>
                    B.I, Certificado e Atestado Médico devem estar em <b>PDF</b> (máx. 1MB cada).
                </div>
            </div>
            <button type="submit" name="cadastrar" class="btn btn-primary w-100 py-2">
                <i class="bi bi-send"></i> Enviar Matrícula
            </button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Validação de tamanho para todos os inputs
document.querySelectorAll('.file-input').forEach(function(input) {
    input.addEventListener('change', function () {
        const file = this.files[0];
        if (file && file.size > 1 * 1024 * 1024) {
            alert('Erro: O arquivo "' + file.name + '" excede o tamanho máximo de 1 MB.');
            this.value = '';
        }
    });
});
</script>
<?php include_once "footer.php"; ?>
</body>
</html>
<?php ob_end_flush(); ?>
