<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include_once "head.php"; ?>
    <style>
        body {
            background: linear-gradient(120deg, #f8fafc 60%, #e3e9f7 100%);
        }
        .noticia-detalhada-section {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 32px rgba(30,64,175,0.10), 0 2px 8px rgba(0,0,0,0.04);
            padding: 3.5rem 2.5rem 2.5rem 2.5rem;
            margin: 3rem auto 2.5rem auto;
            max-width: 900px;
            position: relative;
            overflow: hidden;
        }
        .noticia-imagem-container {
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
        .noticia-imagem {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 18px;
            transition: transform 0.3s;
        }
        .noticia-imagem:hover {
            transform: scale(1.03);
        }
        .noticia-titulo {
            color: #1e293b;
            margin-bottom: 1.1rem;
            font-size: 2.7rem;
            font-weight: 800;
            letter-spacing: 1px;
            line-height: 1.15;
            text-shadow: 0 2px 8px #e3e9f7;
        }
        .noticia-underline {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
            border-radius: 2px;
            margin-bottom: 2rem;
            display: block;
        }
        .noticia-meta {
            background: linear-gradient(90deg, #f8fafc 60%, #e3e9f7 100%);
            padding: 0.9rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 2.2rem;
            display: flex;
            align-items: center;
            gap: 2.2rem;
            box-shadow: 0 2px 8px rgba(30,64,175,0.06);
        }
        .noticia-meta span {
            display: flex;
            align-items: center;
            color: #1e40af;
            font-size: 1.01rem;
            font-weight: 500;
            letter-spacing: 0.2px;
        }
        .noticia-meta i {
            margin-right: 8px;
            color: #0ea5e9;
            font-size: 1.1rem;
        }
        .noticia-conteudo {
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
            .noticia-detalhada-section {
                padding: 1.2rem 0.5rem 1.5rem 0.5rem;
            }
            .noticia-titulo {
                font-size: 1.6rem;
            }
            .noticia-imagem-container {
                height: 180px;
            }
            .noticia-conteudo {
                padding: 1.1rem 0.7rem;
                font-size: 1.01rem;
            }
            .noticia-meta {
                flex-direction: column;
                gap: 0.7rem;
                align-items: flex-start;
                padding: 0.7rem 1rem;
            }
            .share-links {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.7rem;
                margin-top: 0.5rem;
            }
            .voltar-btn {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <?php 
    include_once "menu_principal.php";
    include_once "admin/models/conexao.php";
    
    if (!isset($_GET['id'])) {
        header("Location: noticia.php");
        exit;
    }
    
    $id = $_GET['id'];
    $sql = "SELECT * FROM noticias WHERE id = ? AND stato = 'Ativo'";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $noticia = $resultado->fetch_assoc();
    
    if (!$noticia) {
        header("Location: noticia.php");
        exit;
    }

    if (!$noticia) {
        echo "Erro na consulta: " . $conexao->error;
        exit;
    }

    // Montar a URL da notícia para partilha
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $dominio = $_SERVER['HTTP_HOST'];
    $caminho = $_SERVER['REQUEST_URI'];
    $url_noticia = $protocolo . $dominio . $caminho;
    $titulo_noticia = isset($noticia['titulo']) ? $noticia['titulo'] : '';
    ?>
    
    <div class="noticia-detalhada-section">
        <?php if(!empty($noticia['url_imagem'])): ?>
            <div class="noticia-imagem-container">
                <img src="admin/<?php echo htmlspecialchars($noticia['url_imagem']); ?>" 
                     alt="<?php echo htmlspecialchars($noticia['titulo']); ?>" 
                     class="noticia-imagem">
            </div>
        <?php endif; ?>
        
        <h1 class="noticia-titulo"><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        <span class="noticia-underline"></span>
        
        <!-- Links de partilha -->
        <div class="share-links">
            <span>Partilhar:</span>
            <a class="share-link-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url_noticia); ?>" target="_blank" title="Partilhar no Facebook" rel="noopener">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a class="share-link-btn" href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url_noticia); ?>&text=<?php echo urlencode($titulo_noticia); ?>" target="_blank" title="Partilhar no Twitter" rel="noopener">
                <i class="fab fa-twitter"></i>
            </a>
            <a class="share-link-btn" href="https://api.whatsapp.com/send?text=<?php echo urlencode($titulo_noticia . ' ' . $url_noticia); ?>" target="_blank" title="Partilhar no WhatsApp" rel="noopener">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a class="share-link-btn" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($url_noticia); ?>&title=<?php echo urlencode($titulo_noticia); ?>" target="_blank" title="Partilhar no LinkedIn" rel="noopener">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <button class="share-link-btn" id="copyLinkBtn" title="Copiar link">
                <i class="fa fa-link"></i>
            </button>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var copyBtn = document.getElementById('copyLinkBtn');
                if(copyBtn) {
                    copyBtn.addEventListener('click', function() {
                        var url = "<?php echo htmlspecialchars($url_noticia); ?>";
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(url).then(function() {
                                copyBtn.innerHTML = '<i class="fa fa-check"></i>';
                                setTimeout(function() {
                                    copyBtn.innerHTML = '<i class="fa fa-link"></i>';
                                }, 1500);
                            });
                        } else {
                            // fallback para browsers antigos
                            var tempInput = document.createElement('input');
                            tempInput.value = url;
                            document.body.appendChild(tempInput);
                            tempInput.select();
                            document.execCommand('copy');
                            document.body.removeChild(tempInput);
                            copyBtn.innerHTML = '<i class="fa fa-check"></i>';
                            setTimeout(function() {
                                copyBtn.innerHTML = '<i class="fa fa-link"></i>';
                            }, 1500);
                        }
                    });
                }
            });
        </script>
        <!-- Fim dos links de partilha -->
        
        <div class="noticia-meta">
            <span>
                <i class="fa fa-calendar"></i>
                <?php echo date('d/m/Y', strtotime($noticia['data_publicacao'])); ?>
            </span>
            <?php if(!empty($noticia['autor'])): ?>
            <span>
                <i class="fa fa-user"></i>
                <?php echo htmlspecialchars($noticia['autor']); ?>
            </span>
            <?php endif; ?>
        </div>
        
        <div class="noticia-conteudo">
            <?php echo nl2br(htmlspecialchars($noticia['conteudo'])); ?>
        </div>
        
        <div class="voltar-btn">
            <a href="noticia.php" class="btn">
                <i class="fa fa-arrow-left"></i> Voltar para Notícias
            </a>
        </div>
    </div>
    
    <?php include_once "footer.php"; ?>
</body>
</html> 