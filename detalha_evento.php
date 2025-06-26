<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include_once "head.php"; ?>
    <style>
        body {
            background: linear-gradient(120deg, #f8fafc 60%, #e3e9f7 100%);
        }
        .evento-detalhado-section {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 32px rgba(30,64,175,0.10), 0 2px 8px rgba(0,0,0,0.04);
            padding: 3.5rem 2.5rem 2.5rem 2.5rem;
            margin: 3rem auto 2.5rem auto;
            max-width: 900px;
            position: relative;
            overflow: hidden;
        }
        .evento-imagem-container {
            width: 100%;
            height: 340px;
            background: #f8f9fa;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(30,64,175,0.08);
            margin-bottom: 2.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .evento-imagem {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 18px;
            transition: transform 0.3s;
        }
        .evento-imagem:hover {
            transform: scale(1.03);
        }
        .evento-titulo {
            color: #1e293b;
            margin-bottom: 1.1rem;
            font-size: 2.7rem;
            font-weight: 800;
            letter-spacing: 1px;
            line-height: 1.15;
            text-shadow: 0 2px 8px #e3e9f7;
        }
        .evento-underline {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
            border-radius: 2px;
            margin-bottom: 2rem;
            display: block;
        }
        .evento-meta {
            background: linear-gradient(90deg, #f8fafc 60%, #e3e9f7 100%);
            border-radius: 12px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 2.2rem;
            display: flex;
            gap: 2.5rem;
            align-items: center;
            box-shadow: 0 1px 8px rgba(30,64,175,0.04);
            font-size: 1.13rem;
        }
        .evento-meta span {
            display: flex;
            align-items: center;
            color: #1e40af;
            font-size: 1.01rem;
            font-weight: 500;
            letter-spacing: 0.2px;
        }
        .evento-meta i {
            margin-right: 8px;
            color: #0ea5e9;
            font-size: 1.1rem;
        }
        .evento-descricao {
            text-align: justify;
            line-height: 2.0;
            color: #374151;
            font-size: 1.18rem;
            background: #f8fafc;
            border-radius: 12px;
            padding: 2rem 1.5rem;
            box-shadow: 0 1px 8px rgba(30,64,175,0.04);
            margin-bottom: 2.5rem;
            min-height: 180px;
        }
        .share-links {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            margin-bottom: 2.2rem;
            margin-top: -1.2rem;
        }
        .share-links span {
            font-weight: 600;
            color: #1e40af;
            font-size: 1.08rem;
            margin-right: 0.5rem;
        }
        .share-link-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e3e9f7;
            color: #1e40af;
            font-size: 1.25rem;
            border: none;
            transition: background 0.18s, color 0.18s;
            text-decoration: none;
        }
        .share-link-btn:hover {
            background: #2563eb;
            color: #fff;
        }
        .voltar-btn {
            margin-top: 0.5rem;
            text-align: right;
        }
        .voltar-btn .btn {
            padding: 12px 32px;
            font-size: 1.13rem;
            font-weight: 700;
            border-radius: 8px;
            background: linear-gradient(90deg, #2563eb 60%, #1e40af 100%);
            color: #fff;
            border: none;
            box-shadow: 0 2px 8px #1e40af22;
            transition: background 0.18s, color 0.18s, box-shadow 0.18s;
        }
        .voltar-btn .btn:hover {
            background: linear-gradient(90deg, #1e40af 60%, #2563eb 100%);
            color: #ffd700;
            box-shadow: 0 4px 16px #1e40af33;
        }
        @media (max-width: 767.98px) {
            .evento-detalhado-section {
                padding: 1.2rem 0.5rem 1.5rem 0.5rem;
            }
            .evento-titulo {
                font-size: 1.6rem;
            }
            .evento-imagem-container {
                height: 180px;
            }
            .evento-descricao {
                padding: 1.1rem 0.7rem;
                font-size: 1.01rem;
            }
            .evento-meta {
                flex-direction: column;
                gap: 0.7rem;
                align-items: flex-start;
                padding: 0.7rem 1rem;
            }
            .share-links {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <?php 
    include_once "menu_principal.php";
    include_once "admin/models/conexao.php";
    
    if (!isset($_GET['id'])) {
        header("Location: evento.php");
        exit;
    }
    
    $id = $_GET['id'];
    $sql = "SELECT * FROM eventos WHERE id = ? AND stato = 'Ativo'";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $evento = $resultado->fetch_assoc();
    
    if (!$evento) {
        header("Location: evento.php");
        exit;
    }

    // Dados para partilha
    $url_pagina = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $titulo_partilha = isset($evento['titulo']) ? $evento['titulo'] : '';
    $descricao_partilha = isset($evento['descricao']) ? mb_substr(strip_tags($evento['descricao']), 0, 120, 'UTF-8') : '';
    ?>
    
    <div class="evento-detalhado-section">
        <div class="evento-imagem-container">
            <img src="admin/<?php echo htmlspecialchars($evento['url_imagem']); ?>" 
                 alt="<?php echo htmlspecialchars($evento['titulo']); ?>" 
                 class="evento-imagem">
        </div>
        <h1 class="evento-titulo"><?php echo htmlspecialchars($evento['titulo']); ?></h1>
        <span class="evento-underline"></span>
        <div class="evento-meta">
            <span>
                <i class="fas fa-calendar"></i>
                <?php echo date('d/m/Y H:i', strtotime($evento['data_evento'])); ?>
            </span>
            <span>
                <i class="fas fa-map-marker-alt"></i>
                <?php echo htmlspecialchars($evento['localizacao']); ?>
            </span>
        </div>
        <!-- Botões de partilha -->
        <div class="share-links">
            <span>Partilhar:</span>
            <a class="share-link-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url_pagina); ?>" target="_blank" title="Partilhar no Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a class="share-link-btn" href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url_pagina); ?>&text=<?php echo urlencode($titulo_partilha); ?>" target="_blank" title="Partilhar no Twitter">
                <i class="fab fa-twitter"></i>
            </a>
            <a class="share-link-btn" href="https://api.whatsapp.com/send?text=<?php echo urlencode($titulo_partilha . ' - ' . $url_pagina); ?>" target="_blank" title="Partilhar no WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a class="share-link-btn" href="mailto:?subject=<?php echo urlencode($titulo_partilha); ?>&body=<?php echo urlencode($titulo_partilha . ' - ' . $url_pagina); ?>" title="Partilhar por Email">
                <i class="fas fa-envelope"></i>
            </a>
            <button class="share-link-btn" onclick="navigator.clipboard.writeText('<?php echo htmlspecialchars($url_pagina); ?>');this.innerHTML='<i class=\'fas fa-check\'></i>';" title="Copiar link">
                <i class="fas fa-link"></i>
            </button>
        </div>
        <div class="evento-descricao">
            <?php echo nl2br(htmlspecialchars($evento['descricao'])); ?>
        </div>
        <div class="voltar-btn">
            <a href="evento.php" class="btn">
                <i class="fas fa-arrow-left"></i> Voltar para Eventos
            </a>
        </div>
    </div>
    
    <?php include_once "footer.php"; ?>
</body>
</html> 