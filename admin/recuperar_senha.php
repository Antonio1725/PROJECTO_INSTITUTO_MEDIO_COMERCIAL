<?php
// Página de recuperação de senha

// Configurações do banco de dados


$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $nome_completo = trim($_POST['nome_completo'] ?? '');
    $nome_usual = trim($_POST['nome_usual'] ?? '');

    if (!empty($email) && !empty($nome_completo)) {
        // Conectar ao banco de dados usando mysqli
        include_once 'models/conexao.php';
        $mysqli = $conexao;

        if ($mysqli->connect_errno) {
            $mensagem = '<div style="color: red; margin-bottom: 15px;">Erro ao conectar ao banco de dados.</div>';
        } else {
            // Ajustar charset
            $mysqli->set_charset($charset);

            // Verificar se o usuário existe
            $sql = "SELECT id, email FROM usuarios WHERE email = ? AND nome_completo = ?";
            $params = [$email, $nome_completo];
            $types = "ss";
            if (!empty($nome_usual)) {
                $sql .= " AND nome_usual = ?";
                $params[] = $nome_usual;
                $types .= "s";
            }

            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
                $usuario = $result->fetch_assoc();
                $stmt->close();

                if ($usuario) {
                    // Gerar token de recuperação
                    $token = bin2hex(random_bytes(32));
                    $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    // Salvar token no banco
                    $stmt2 = $mysqli->prepare("UPDATE usuarios SET token_recuperacao = ?, token_expira = ? WHERE id = ?");
                    if ($stmt2) {
                        $stmt2->bind_param("ssi", $token, $expira, $usuario['id']);
                        $stmt2->execute();
                        $stmt2->close();

                        // Montar link de recuperação
                        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
                        $link .= "://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/nova_senha.php?token=$token";

                        // Enviar e-mail
                        $assunto = "Recuperação de Senha";
                        $mensagem_email = "Olá,\n\nRecebemos uma solicitação para redefinir sua senha. Para criar uma nova senha, acesse o link abaixo:\n\n$link\n\nSe você não solicitou, ignore este e-mail.\n\nAtenciosamente,\nEquipe";
                        $headers = "From: no-reply@seudominio.com\r\n";

                        if (mail($usuario['email'], $assunto, $mensagem_email, $headers)) {
                            $mensagem = '<div style="color: green; margin-bottom: 15px;">Se os dados estiverem corretos, as instruções de recuperação foram enviadas para seu e-mail.</div>';
                        } else {
                            $mensagem = '<div style="color: red; margin-bottom: 15px;">Erro ao enviar o e-mail de recuperação. Tente novamente mais tarde.</div>';
                        }
                    } else {
                        $mensagem = '<div style="color: red; margin-bottom: 15px;">Erro ao salvar o token de recuperação.</div>';
                    }
                } else {
                    $mensagem = '<div style="color: red; margin-bottom: 15px;">Usuário não encontrado com os dados informados.</div>';
                }
            } else {
                $mensagem = '<div style="color: red; margin-bottom: 15px;">Erro ao preparar consulta.</div>';
            }
            $mysqli->close();
        }
    } else {
        $mensagem = '<div style="color: red; margin-bottom: 15px;">Por favor, preencha todos os campos obrigatórios.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .recuperar-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(58,123,213,0.10);
            width: 100%;
            max-width: 370px;
            text-align: center;
        }
        .recuperar-container h2 {
            color: #3a7bd5;
            margin-bottom: 18px;
            font-weight: 600;
        }
        .recuperar-container p {
            color: #555;
            font-size: 1em;
            margin-bottom: 22px;
        }
        .recuperar-container input[type="email"],
        .recuperar-container input[type="text"] {
            width: 100%;
            padding: 14px 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1em;
            margin-bottom: 18px;
            outline: none;
            transition: border 0.3s;
        }
        .recuperar-container input[type="email"]:focus,
        .recuperar-container input[type="text"]:focus {
            border-color: #3a7bd5;
        }
        .recuperar-container button {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #3a7bd5, #00d2ff);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1.05em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .recuperar-container button:hover {
            background: linear-gradient(135deg, #00d2ff, #3a7bd5);
        }
        .recuperar-container a {
            display: inline-block;
            margin-top: 18px;
            color: #3a7bd5;
            text-decoration: none;
            font-size: 0.97em;
        }
        .recuperar-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="recuperar-container">
        <h2>Recuperar Senha</h2>
        <p>Informe seus dados cadastrados para receber instruções de recuperação.</p>
        <?php if ($mensagem) echo $mensagem; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Seu e-mail" required autofocus>
            <input type="text" name="nome_completo" placeholder="Nome completo" required>
            <input type="text" name="nome_usual" placeholder="Nome usual (opcional)">
            <button type="submit">Enviar Instruções</button>
        </form>
        <a href="index">&larr; Voltar ao login</a>
    </div>
</body>
</html> 