<!DOCTYPE html>
<html lang="pt">

<head>
    <?php include_once "head.php"; ?>
    <style>
        body {
            background: linear-gradient(120deg, #f8fafc 60%, #e3e9f7 100%);
        }
        .noticias-section {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 32px rgba(30,64,175,0.10), 0 2px 8px rgba(0,0,0,0.04);
            padding: 3.5rem 2.5rem 2.5rem 2.5rem;
            margin: 3rem auto 2.5rem auto;
            max-width: 1200px;
            position: relative;
            overflow: hidden;
        }
        .noticias-title {
            color: #1e293b;
            margin-bottom: 1.1rem;
            font-size: 2.7rem;
            font-weight: 800;
            letter-spacing: 1px;
            line-height: 1.15;
            text-shadow: 0 2px 8px #e3e9f7;
        }
        .noticias-underline {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
            border-radius: 2px;
            margin-bottom: 2rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .noticia-card {
            background: linear-gradient(120deg, #f8fafc 60%, #e3e9f7 100%);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(30,64,175,0.08), 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
            margin-bottom: 30px;
            transition: transform 0.25s, box-shadow 0.25s;
            position: relative;
            min-height: 420px;
            display: flex;
            flex-direction: column;
        }
        .noticia-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 32px rgba(30,64,175,0.13), 0 4px 16px rgba(0,0,0,0.06);
        }
        .noticia-img-container {
            width: 100%;
            height: 220px;
            background: #f8f9fa;
            border-radius: 16px 16px 0 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .noticia-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 16px 16px 0 0;
            transition: transform 0.3s;
        }
        .noticia-card:hover .noticia-img {
            transform: scale(1.04);
        }
        .noticia-body {
            padding: 2rem 1.2rem 1.2rem 1.2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .noticia-card-title {
            font-size: 1.45rem;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 0.7rem;
            min-height: 56px;
        }
        .noticia-desc {
            color: #374151;
            font-size: 1.08rem;
            text-align: justify;
            margin-bottom: 1.1rem;
            flex: 1;
        }
        .noticia-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1.2rem;
        }
        .noticia-date-badge {
            background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
            color: #fff;
            font-size: 0.98rem;
            border-radius: 8px;
            padding: 0.35rem 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .noticia-btn {
            background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.2rem;
            font-size: 1.01rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(30,64,175,0.08);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .noticia-btn:hover {
            background: linear-gradient(90deg, #0ea5e9 60%, #1e40af 100%);
            color: #fff;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(30,64,175,0.13);
        }
        @media (max-width: 991px) {
            .noticia-card { min-height: 380px; }
            .noticia-img-container { height: 180px; }
        }
        @media (max-width: 767px) {
            .noticias-section { padding: 2rem 0.5rem 1.5rem 0.5rem; }
            .noticia-card { min-height: 320px; }
            .noticia-img-container { height: 140px; }
        }
    </style>
</head>

<body>
<?php
include_once "menu_principal.php";
include_once "admin/models/conexao.php";
?>

<div class="container-xxl py-5">
    <div class="noticias-section">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="noticias-title">Notícias</h1>
            <span class="noticias-underline"></span>
        </div>
        <div class="row g-4">
            <?php
            // Conexão com o banco de dados
            $conn = $conexao;

            // Query para buscar as últimas notícias ativas
            $sql = "
                SELECT 
                    titulo, 
                    conteudo, 
                    data_publicacao, 
                    url_imagem,
                    id
                FROM 
                    noticias 
                WHERE 
                    stato = 'Ativo' 
                ORDER BY 
                    data_publicacao DESC 
            ";

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $delay = 0.1;
                while ($row = $result->fetch_assoc()) {
                    // Imagem padrão se não houver imagem
                    $imagem = !empty($row['url_imagem']) ? "admin/" . htmlspecialchars($row['url_imagem']) : "img/sem-imagem.jpg";
                    $data_formatada = date('d/m/Y', strtotime($row['data_publicacao']));
                    $descricao = mb_substr(strip_tags($row['conteudo']), 0, 120, 'UTF-8');
                    if (mb_strlen(strip_tags($row['conteudo']), 'UTF-8') > 120) {
                        $descricao .= '...';
                    }
                    $titulo = htmlspecialchars($row['titulo']);
                    $id_noticia = isset($row['id']) ? (int)$row['id'] : 0;
                    ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?= number_format($delay, 1) ?>s">
                        <div class="noticia-card">
                            <div class="noticia-img-container">
                                <img class="noticia-img" src="<?= $imagem ?>" alt="Imagem da notícia">
                            </div>
                            <div class="noticia-body">
                                <h4 class="noticia-card-title"><?= $titulo ?></h4>
                                <p class="noticia-desc"><?= htmlspecialchars($descricao) ?></p>
                                <div class="noticia-footer">
                                    <span class="noticia-date-badge"><i class="fa fa-calendar"></i> <?= $data_formatada ?></span>
                                    <a href="detalha_noticia.php?id=<?= $id_noticia ?>" class="noticia-btn">
                                        <i class="fa fa-arrow-right"></i> Saiba Mais
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $delay += 0.1;
                }
            } else {
                echo '<div class="col-12 text-center"><p>Nenhuma notícia encontrada.</p></div>';
            }
            ?>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>
</body>
</html>
