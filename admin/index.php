<?php
include_once "models/conexao.php";
include_once "models/Usuarios.php";
session_start();

$alert_script = ''; // Variável para guardar o script do SweetAlert

// Verifica se o formulário foi submetido
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $usuario = new Usuarios($conexao);
        $u = $usuario->login($username, $password);

        if ($u) {
            // Redireciona em caso de sucesso e termina o script
            echo "<script>window.location.href = 'admin';</script>";
            exit();
        } else {
            // Prepara o alerta de erro
            $alert_script = "
                Swal.fire({
                  title: 'Erro de Acesso',
                  text: 'Usuário ou senha inválidos. Tente novamente.',
                  icon: 'error',
                  confirmButtonText: 'Tentar Novamente',
                  confirmButtonColor: '#3a7bd5'
                });
            ";
        }
    } else {
        // Prepara o alerta de aviso
        $alert_script = "
            Swal.fire({
              title: 'Atenção',
              text: 'Por favor, preencha todos os campos.',
              icon: 'warning',
              confirmButtonText: 'OK',
              confirmButtonColor: '#3a7bd5'
            });
        ";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Painel Administrativo</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #3a7bd5;
            --secondary-color: #00d2ff;
            --dark-grey: #333;
            --text-color: #555;
            --white: #ffffff;
            --light-grey-bg: #f7f7f7;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--light-grey-bg);
            color: var(--text-color);
        }

        .login-container {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            width: 950px;
            max-width: 95vw;
            height: 650px;
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .login-info-panel {
            background: linear-gradient(to bottom, rgba(58, 123, 213, 0.9), rgba(0, 210, 255, 0.9)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1170') no-repeat center center/cover;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: var(--white);
        }
        
        .login-info-panel .icon {
            font-size: 4rem;
            margin-bottom: 20px;
            text-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .login-info-panel h1 {
            font-size: 2.2rem;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .login-info-panel p {
            margin-top: 15px;
            font-size: 1.1rem;
            font-weight: 300;
            max-width: 350px;
        }

        .login-form-panel {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .formLogin {
            width: 100%;
            max-width: 350px;
            text-align: center;
        }

        .formLogin h2 {
            color: var(--dark-grey);
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .formLogin > p {
            color: #777;
            margin-bottom: 35px;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group .icon-input {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
            transition: color 0.3s ease;
        }

        .input-group input {
            width: 100%;
            padding: 16px 16px 16px 50px;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.2);
        }

        .input-group input:focus ~ .icon-input {
            color: var(--primary-color);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(58, 123, 213, 0.4);
        }

        @media (max-width: 950px) {
            .login-container {
                grid-template-columns: 1fr;
                width: 100%;
                height: 100%;
                max-width: 100vw;
                max-height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }
            .login-info-panel {
                display: none;
            }
            .login-form-panel {
                background: var(--white);
            }
        }
         @media (max-width: 480px) {
            .formLogin {
                padding: 20px;
            }
         }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-info-panel">
            <i class="fas fa-school icon"></i>
            <h1>Painel do Instituto</h1>
            <p>Bem-vindo ao sistema de gestão. Faça login para aceder às ferramentas administrativas.</p>
        </div>
        <div class="login-form-panel">
            <form method="POST" class="formLogin">
                <h2>Acessar Painel</h2>
                <p>Bem-vindo de volta! Faça login para continuar.</p>
                
                <div class="input-group">
                    <input type="text" name="username" id="username" placeholder="Usuário" required autofocus="true" />
                    <i class="fas fa-user icon-input"></i>
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder="Senha" required />
                    <i class="fas fa-lock icon-input"></i>
                </div>

                <button type="submit" name="login" class="btn-login">Entrar</button>
            </form>
        </div>
    </div>

    <?php
    // Exibe o script de alerta se houver algum
    if (!empty($alert_script)) {
        echo "<script>$alert_script</script>";
    }
    ?>
</body>
</html>
