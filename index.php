<!DOCTYPE html>
<html lang="pt">

<head>
    <?php
    include_once "head.php";

    ?>
    


</head>

<body>
<?php
include_once "admin/models/conexao.php";
include_once "menu_principal.php";

?>

<!-- Header Start -->
<div class="container-fluid position-relative p-0" style="overflow: hidden;">
    <div class="row">
        <div class="col-md-12 p-0">
            <!-- Carousel Start -->
            <div id="headerCarousel" class="carousel slide" data-bs-ride="carousel" style="position: relative;">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/img_2.jpg" class="d-block w-100" alt="Header Image 1" style="height: 600px; object-fit: cover; filter: brightness(0.7);" />
                    </div>
                    <div class="carousel-item">
                        <img src="img/img_3.jpg" class="d-block w-100" alt="Header Image 2" style="height: 600px; object-fit: cover; filter: brightness(0.7);" />
                    </div>
                    <div class="carousel-item">
                        <img src="img/img_4.jpg" class="d-block w-100" alt="Header Image 3" style="height: 600px; object-fit: cover; filter: brightness(0.7);" />
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#headerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#headerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
                <!-- Texto inicial sobre a imagem -->
                <div class="position-absolute top-50 start-50 translate-middle text-center text-white" style="z-index: 2;">
                    <h1 class="display-4 fw-bold mb-3" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.7);">Bem-vindo ao Nosso Instituto</h1>
                    <p class="lead mb-4" style="text-shadow: 1px 1px 6px rgba(0,0,0,0.6);">
                        Transformando vidas através da educação de qualidade e valores sólidos.
                    </p>
                </div>
                <!-- Cards de Missão, Valores e Objectivos -->
                <div class="position-absolute w-100" style="bottom:20px; left: 0; z-index: 3;">
                    <div class="container">
                        <div class="row justify-content-center g-4">
                            <!-- Missão Card -->
                            <div class="col-12 col-md-4">
                                <div class="card h-100 border-0 shadow-lg rounded-4" style="background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); transition: transform 0.3s ease;">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-bullseye fa-3x text-primary"></i>
                                        </div>
                                        <h5 class="card-title fw-bold mb-2">Missão</h5>
                                        <p class="card-text small">
                                            Oferecer ensino de excelência, promovendo o desenvolvimento intelectual, social e ético dos nossos alunos.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Valores Card -->
                            <div class="col-12 col-md-4">
                                <div class="card h-100 border-0 shadow-lg rounded-4" style="background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); transition: transform 0.3s ease;">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-gem fa-3x text-success"></i>
                                        </div>
                                        <h5 class="card-title fw-bold mb-2">Valores</h5>
                                        <p class="card-text small">
                                            Compromisso, respeito, integridade, inovação e responsabilidade social são os pilares que nos guiam.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Objectivos Card -->
                            <div class="col-12 col-md-4">
                                <div class="card h-100 border-0 shadow-lg rounded-4" style="background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); transition: transform 0.3s ease;">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-rocket fa-3x text-warning"></i>
                                        </div>
                                        <h5 class="card-title fw-bold mb-2">Objectivos</h5>
                                        <p class="card-text small">
                                            Preparar profissionais competentes, cidadãos conscientes e agentes de mudança para a sociedade.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim dos cards -->
            </div>
            <!-- Carousel End -->
        </div>
    </div>
</div>
<!-- Header End -->

<?php

    // Buscar número de vagas para o curso de Informática de Gestão
    $sql = "SELECT * FROM controlovagas WHERE id = 1";
    $v = mysqli_query($conexao, $sql);
    $dados = mysqli_fetch_assoc($v);
    $informatica = (int)$dados['informatica'];
    $comercio = $dados['comercio'];
    $secretariado = $dados['secretariado'];
    $financas = $dados['financas'];
    $contabilidade = $dados['contabilidade'];
    $gestao_recursos_humanos = $dados['gestao_recursos_humanos'];

?>

<!-- Feature Start -->
<style>
    .vagas-card {
        background: linear-gradient(135deg, #ffffffcc 60%, #e3f2fdcc 100%);
        border-radius: 18px;
        box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        position: relative;
        overflow: hidden;
    }
    .vagas-card:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 8px 32px 0 rgba(0,0,0,0.15);
    }
    .vagas-icon {
        font-size: 2.5rem;
        color: #1976d2;
        margin-bottom: 10px;
        display: inline-block;
        background: #e3f2fd;
        border-radius: 50%;
        width: 56px;
        height: 56px;
        line-height: 56px;
        text-align: center;
        box-shadow: 0 2px 8px 0 rgba(25, 118, 210, 0.08);
    }
    .vagas-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1976d2;
        margin-bottom: 0;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .vagas-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 0.2rem;
        letter-spacing: 1px;
    }
    .vagas-section-bg {
        background: linear-gradient(120deg, #1976d2 60%, #64b5f6 100%);
    }
    @media (max-width: 767px) {
        .vagas-card {
            margin-bottom: 1.5rem;
        }
    }
</style>
<div class="container-fluid vagas-section-bg overflow-hidden my-5 px-lg-0 py-5">
    <div class="container feature px-lg-0">
        <div class="row g-0 mx-lg-0 justify-content-center">
            <div class="col-lg-12 feature-text py-3 wow fadeIn" data-wow-delay="0.1s">
                <div class="p-lg-5 ps-lg-0">
                    <h1 class="text-white mb-4 text-center" style="letter-spacing:1px;">VAGAS DISPONÍVEIS</h1>
                    <div class="row g-4 wow fadeInUp justify-content-center" data-wow-delay="0.1s">
                        <?php
                        $cursos = [
                            ['nome' => 'INFORMÁTICA', 'vagas' => $informatica, 'icon' => 'fa-solid fa-laptop-code'],
                            ['nome' => 'COMÉRCIO', 'vagas' => $comercio, 'icon' => 'fa-solid fa-store'],
                            ['nome' => 'SECRETARIADO', 'vagas' => $secretariado, 'icon' => 'fa-solid fa-user-tie'],
                            ['nome' => 'FINANÇAS', 'vagas' => $financas, 'icon' => 'fa-solid fa-coins'],
                            ['nome' => 'CONTABILIDADE', 'vagas' => $contabilidade, 'icon' => 'fa-solid fa-calculator'],
                            ['nome' => 'GESTÃO DE RECURSOS HUMANOS', 'vagas' => $gestao_recursos_humanos, 'icon' => 'fa-solid fa-users']
                        ];
                        foreach ($cursos as $curso): ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-2 d-flex align-items-stretch">
                                <div class="card vagas-card w-100 text-center mb-0">
                                    <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                                        <span class="vagas-icon mb-2">
                                            <i class="<?= htmlspecialchars($curso['icon']) ?>"></i>
                                        </span>
                                        <div class="vagas-number mb-1">
                                            <span class="contador" data-contar="<?= htmlspecialchars($curso['vagas']) ?>">0</span>
                                        </div>
                                        <div class="vagas-title"><?= htmlspecialchars($curso['nome']) ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Feature End -->

<script>
document.addEventListener("DOMContentLoaded", function() {
    function animateCounter(element, endValue, duration) {
        let startValue = 0;
        let startTime = null;
        endValue = parseInt(endValue, 10);

        function step(currentTime) {
            if (!startTime) startTime = currentTime;
            let progress = Math.min((currentTime - startTime) / duration, 1);
            let value = Math.floor(progress * (endValue - startValue) + startValue);
            element.textContent = value;
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                element.textContent = endValue;
            }
        }
        requestAnimationFrame(step);
    }

    document.querySelectorAll('.contador').forEach(function(el) {
        let endValue = el.getAttribute('data-contar');
        animateCounter(el, endValue, 1200);
    });
});
</script>







<!-- Service Start -->
<style>
    .noticia-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 6px 32px rgba(30,64,175,0.10), 0 1.5px 4px rgba(0,0,0,0.04);
        transition: transform 0.22s cubic-bezier(.4,0,.2,1), box-shadow 0.22s cubic-bezier(.4,0,.2,1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: none;
        position: relative;
    }
    .noticia-card:hover {
        transform: translateY(-10px) scale(1.025);
        box-shadow: 0 12px 40px rgba(30,64,175,0.16), 0 2px 8px rgba(0,0,0,0.06);
    }
    .noticia-img-container {
        height: 210px;
        background: linear-gradient(135deg, #e3e9f7 0%, #f8fafc 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f0;
        position: relative;
    }
    .noticia-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
        border-radius: 0 0 0 0;
    }
    .noticia-card:hover .noticia-img-container img {
        transform: scale(1.08) rotate(-1deg);
    }
    .noticia-content {
        padding: 1.6rem 1.3rem 1.2rem 1.3rem;
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: linear-gradient(180deg, #fff 80%, #f8fafc 100%);
    }
    .noticia-title {
        font-size: 1.18rem;
        font-weight: 700;
        color: #1a2340;
        margin-bottom: 0.7rem;
        min-height: 48px;
        line-height: 1.2;
        letter-spacing: 0.01em;
        text-shadow: 0 1px 0 #f0f4ff;
    }
    .noticia-desc {
        color: #5a5a5a;
        font-size: 1.01rem;
        text-align: justify;
        min-height: 72px;
        margin-bottom: 1.1rem;
        line-height: 1.6;
    }
    .noticia-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        gap: 0.5rem;
    }
    .noticia-date {
        background: #e9f0ff;
        color: #2563eb;
        font-size: 13px;
        border-radius: 12px;
        padding: 0.3em 0.9em;
        font-weight: 500;
        letter-spacing: 0.01em;
        display: flex;
        align-items: center;
        gap: 0.3em;
        box-shadow: 0 1px 2px rgba(37,99,235,0.04);
    }
    .noticia-btn {
        background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%);
        color: #fff !important;
        border: none;
        border-radius: 20px;
        padding: 0.45em 1.3em;
        font-size: 1.01rem;
        font-weight: 600;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(37,99,235,0.08);
        display: flex;
        align-items: center;
        gap: 0.4em;
        text-shadow: 0 1px 0 #1e40af33;
    }
    .noticia-btn:hover, .noticia-btn:focus {
        background: linear-gradient(90deg, #1e40af 0%, #2563eb 100%);
        color: #fff !important;
        text-decoration: none;
        box-shadow: 0 4px 16px rgba(37,99,235,0.13);
    }
    .noticia-badge-top {
        position: absolute;
        top: 14px;
        left: 14px;
        background: #2563eb;
        color: #fff;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.25em 0.9em;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(37,99,235,0.10);
        z-index: 2;
        letter-spacing: 0.01em;
        opacity: 0.93;
    }
    @media (max-width: 991px) {
        .noticia-img-container { height: 170px; }
    }
    @media (max-width: 767px) {
        .noticia-img-container { height: 140px; }
        .noticia-content { padding: 1rem 0.7rem 0.9rem 0.7rem; }
    }
</style>
<div class="container-xxl py-5" style="background: linear-gradient(180deg, #f8fafc 0%, #f0f4ff 100%); border-radius: 32px;">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 style="font-weight: 800; color: #1a2340; letter-spacing: 0.01em; text-shadow: 0 2px 0 #e9f0ff;">Notícias Mais Recentes</h1>
            <p class="text-muted" style="font-size: 1.13rem; margin-top: 0.5rem;">Fique por dentro das novidades e acontecimentos da nossa escola.</p>
        </div>
        <div class="row g-4">
            <?php
            // Conexão com o banco de dados
            $conn = $conexao;

            // Query para buscar as últimas 3 notícias ativas
            $sql = "SELECT 
                id,
                titulo, 
                conteudo, 
                data_publicacao, 
                url_imagem 
            FROM 
                noticias 
            WHERE 
                stato = 'Ativo' 
            ORDER BY 
                data_publicacao DESC 
            LIMIT 3";

            $result = $conn->query($sql);

            // Exibe os dados formatados
            if ($result && $result->num_rows > 0) {
                $delay = 0.1;
                $badgeLabels = ['Destaque', 'Atual', 'Novo'];
                $badgeColors = ['#2563eb', '#1e40af', '#0ea5e9'];
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    // Define valores padrão se a URL da imagem não for fornecida
                    $imagem = !empty($row['url_imagem']) ? "admin/" . htmlspecialchars($row['url_imagem']) : "img/sem-imagem.jpg";
                    $data_formatada = date('d/m/Y', strtotime($row['data_publicacao']));
                    $descricao = mb_substr(strip_tags($row['conteudo']), 0, 120, 'UTF-8');
                    if (mb_strlen(strip_tags($row['conteudo']), 'UTF-8') > 120) {
                        $descricao .= '...';
                    }
                    $titulo = htmlspecialchars($row['titulo']);
                    $id_noticia = isset($row['id']) ? (int)$row['id'] : 0;
                    $badge = $badgeLabels[$i % count($badgeLabels)];
                    $badgeColor = $badgeColors[$i % count($badgeColors)];
                    ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?= number_format($delay, 1) ?>s">
                        <div class="noticia-card">
                            <span class="noticia-badge-top" style="background: <?= $badgeColor ?>;"><?= $badge ?></span>
                            <div class="noticia-img-container">
                                <img src="<?= $imagem ?>" alt="Imagem da notícia">
                            </div>
                            <div class="noticia-content">
                                <div class="noticia-title"><?= $titulo ?></div>
                                <div class="noticia-desc"><?= htmlspecialchars($descricao) ?></div>
                                <div class="noticia-footer">
                                    <span class="noticia-date"><i class="fa fa-calendar me-1"></i> <?= $data_formatada ?></span>
                                    <a class="noticia-btn" href="detalha_noticia?id=<?= $id_noticia ?>">
                                        <i class="fa fa-plus"></i> Saiba Mais
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $delay += 0.1;
                    $i++;
                }
            } else {
                echo '<div class="col-12"><p class="text-center text-muted">Nenhuma notícia encontrada.</p></div>';
            }
            ?>
        </div>
    </div>
</div>
<!-- Service End -->

<!-- About Start -->
<style>
    .about-section {
        background: linear-gradient(135deg, #f8fafc 60%, #e3e9f7 100%);
        border-radius: 30px;
        box-shadow: 0 8px 32px rgba(30,64,175,0.10), 0 2px 8px rgba(0,0,0,0.04);
        padding: 3rem 2rem;
        margin-bottom: 3rem;
        overflow: hidden;
    }
    .about-images {
        position: relative;
        min-height: 340px;
        display: flex;
        align-items: flex-end;
        justify-content: flex-end;
    }
    .about-img-main {
        width: 80%;
        border-radius: 20px;
        box-shadow: 0 6px 32px rgba(30,64,175,0.10);
        position: relative;
        z-index: 2;
    }
    .about-img-overlay {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 55%;
        border-radius: 16px;
        border: 6px solid #fff;
        box-shadow: 0 2px 16px rgba(30,64,175,0.08);
        z-index: 1;
        transform: translateY(30%);
        background: #fff;
    }
    .about-badge {
        background: linear-gradient(90deg, #2563eb 60%, #0ea5e9 100%);
        color: #fff;
        font-weight: 600;
        letter-spacing: 1px;
        font-size: 1rem;
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        display: inline-block;
        margin-bottom: 1.2rem;
        box-shadow: 0 2px 8px rgba(30,64,175,0.08);
    }
    .about-title {
        font-size: 2.3rem;
        font-weight: 800;
        color: #1a2340;
        margin-bottom: 1.2rem;
        letter-spacing: -1px;
    }
    .about-desc {
        color: #444;
        font-size: 1.08rem;
        line-height: 1.8;
        margin-bottom: 2rem;
        text-align: justify;
    }
    .about-btn {
        background: linear-gradient(90deg, #2563eb 60%, #0ea5e9 100%);
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 50px;
        padding: 0.85rem 2.5rem;
        font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(30,64,175,0.10);
        transition: background 0.2s, transform 0.2s;
    }
    .about-btn:hover {
        background: linear-gradient(90deg, #0ea5e9 60%, #2563eb 100%);
        transform: translateY(-2px) scale(1.03);
        color: #fff;
    }
    @media (max-width: 991px) {
        .about-section {
            padding: 2rem 1rem;
        }
        .about-title {
            font-size: 1.7rem;
        }
        .about-images {
            min-height: 220px;
        }
        .about-img-main, .about-img-overlay {
            width: 90%;
        }
    }
    @media (max-width: 767px) {
        .about-section {
            border-radius: 16px;
        }
        .about-images {
            min-height: 160px;
        }
        .about-img-main, .about-img-overlay {
            width: 100%;
        }
    }
</style>
<div class="container-xxl py-5">
    <div class="container about-section">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="about-images">
                    <img class="img-fluid about-img-main" src="img/about.jpg" alt="Sobre a Escola Comercial de Luanda">
                    <img class="img-fluid about-img-overlay" src="img/about2.jpg" alt="Ambiente Escolar">
                </div>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <span class="about-badge">Sobre nós</span>
                <h1 class="about-title">Quem nós somos</h1>
                <p class="about-desc">
                    A Escola Comercial de Luanda é um projeto inovador dedicado à formação de profissionais na área de comércio, localizado na pulsante capital de Angola. Com o objetivo de desenvolver competências essenciais, a escola oferece um currículo abrangente que inclui disciplinas como gestão, marketing, contabilidade e empreendedorismo, preparando seus alunos para as crescentes demandas do mercado.<br><br>
                    Em um momento em que Angola se empenha em diversificar sua economia, tradicionalmente dependente do petróleo, a Escola Comercial se destaca como uma peça fundamental na transformação do sistema educacional do país. Junto a outras instituições, como o Instituto Médio de Economia de Luanda e o Instituto Politécnico Industrial de Luanda, a Escola Comercial de Luanda forma um ambiente propício para o aprendizado e inovação, proporcionando aos jovens as ferramentas necessárias para se tornarem agentes de mudança e contribuírem para um futuro mais próspero e sustentável.
                </p>
                <a class="about-btn" href="about">Saiba Mais</a>
            </div>
        </div>
    </div>
</div>
<!-- About End -->


<!-- Service Start -->
<style>
    .service-section {
        background: linear-gradient(120deg, #f8fafc 60%, #e3e9f7 100%);
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(30,64,175,0.10), 0 2px 8px rgba(0,0,0,0.04);
        padding: 3rem 0;
        margin-bottom: 2rem;
    }
    .service-title {
        font-size: 2.3rem;
        font-weight: 800;
        color: #1a2340;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }
    .service-underline {
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
        border-radius: 2px;
        margin: 0 auto 2rem auto;
        display: block;
    }
    .service-item {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 6px 32px rgba(30,64,175,0.10), 0 1.5px 4px rgba(0,0,0,0.04);
        transition: transform 0.22s cubic-bezier(.4,0,.2,1), box-shadow 0.22s cubic-bezier(.4,0,.2,1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: none;
        position: relative;
        padding: 2.5rem 2rem 2rem 2rem;
    }
    .service-item:hover {
        transform: translateY(-10px) scale(1.025);
        box-shadow: 0 12px 40px rgba(30,64,175,0.16), 0 2px 8px rgba(0,0,0,0.06);
    }
    .service-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #e3e9f7 0%, #f8fafc 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 1.2rem;
        box-shadow: 0 2px 8px rgba(30,64,175,0.08);
        font-size: 2.1rem;
        color: #1e40af;
        transition: background 0.3s, color 0.3s;
    }
    .service-item:hover .service-icon {
        background: linear-gradient(135deg, #0ea5e9 0%, #1e40af 100%);
        color: #fff;
    }
    .service-item h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a2340;
        margin-bottom: 0.7rem;
        letter-spacing: 0.5px;
    }
    .service-item p {
        color: #4b5563;
        font-size: 1.01rem;
        margin-bottom: 1.5rem;
        flex: 1 1 auto;
        text-align: justify;
    }
    .service-btn {
        background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
        color: #fff !important;
        border-radius: 30px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        transition: background 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(30,64,175,0.08);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }
    .service-btn:hover {
        background: linear-gradient(90deg, #0ea5e9 60%, #1e40af 100%);
        color: #fff;
        box-shadow: 0 4px 16px rgba(30,64,175,0.13);
        text-decoration: none;
    }
    @media (max-width: 991px) {
        .service-item {
            padding: 2rem 1.2rem 1.5rem 1.2rem;
        }
        .service-title {
            font-size: 2rem;
        }
    }
    @media (max-width: 767px) {
        .service-section {
            border-radius: 16px;
            padding: 2rem 0;
        }
        .service-title {
            font-size: 1.5rem;
        }
        .service-item {
            padding: 1.5rem 0.8rem 1.2rem 0.8rem;
        }
    }
</style>
<div class="container-xxl py-5 service-section">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="service-title">Nossos Cursos</h1>
            <span class="service-underline"></span>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fa fa-laptop"></i>
                    </div>
                    <h4>Informática de Gestão</h4>
                    <p>O curso de Informática de Gestão prepara profissionais para integrar tecnologia e gestão, capacitando-os a desenvolver soluções tecnológicas que otimizem processos administrativos, financeiros e organizacionais.</p>
                    <a class="service-btn" href="#"><i class="fa fa-plus"></i>Saiba Mais</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fa fa-calculator"></i>
                    </div>
                    <h4>Contabilidade De Gestão</h4>
                    <p>O curso de Contabilidade de Gestão forma profissionais capazes de planejar, controlar e analisar informações contábeis e financeiras, auxiliando na tomada de decisões estratégicas para a sustentabilidade e crescimento das organizações.</p>
                    <a class="service-btn" href="#"><i class="fa fa-plus"></i>Saiba Mais</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                    </div>
                    <h4>Finança</h4>
                    <p>O curso de Finanças capacita profissionais para gerenciar recursos financeiros, elaborar estratégias de investimentos e análises econômicas, contribuindo para a saúde financeira e o desenvolvimento sustentável das empresas.</p>
                    <a class="service-btn" href="#"><i class="fa fa-plus"></i>Saiba Mais</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fa fa-briefcase"></i>
                    </div>
                    <h4>Secretariado</h4>
                    <p>O curso de Secretariado prepara profissionais para desempenhar funções administrativas, organizacionais e de assessoria, com foco em eficiência, comunicação e gestão do tempo, garantindo suporte essencial às lideranças.</p>
                    <a class="service-btn" href="#"><i class="fa fa-plus"></i>Saiba Mais</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fa fa-line-chart"></i>
                    </div>
                    <h4>Economia</h4>
                    <p>O curso de Economia forma especialistas em analisar mercados, compreender políticas econômicas e desenvolver estratégias para otimizar recursos, promovendo o crescimento sustentável em diferentes setores.</p>
                    <a class="service-btn" href="#"><i class="fa fa-plus"></i>Saiba Mais</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <h4>Gestão de Recursos Humanos</h4>
                    <p>O curso de Gestão de Recursos Humanos capacita profissionais para recrutar, desenvolver e reter talentos, promovendo um ambiente organizacional saudável e alinhado aos objetivos estratégicos das empresas.</p>
                    <a class="service-btn" href="#"><i class="fa fa-plus"></i>Saiba Mais</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Service End -->


<!-- Feature Start -->
<style>
    .feature-section {
        width: 100vw;
        max-width: 100vw;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        position: relative;
        background: linear-gradient(120deg, #2563eb 60%, #1e40af 100%);
        border-radius: 32px;
        box-shadow: 0 8px 32px rgba(30,64,175,0.13), 0 2px 8px rgba(0,0,0,0.04);
        overflow: hidden;
        margin-bottom: 3rem;
    }
    .feature-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: 1px;
        margin-bottom: 1.2rem;
        text-shadow: 0 2px 0 #1e40af33;
    }
    .feature-desc {
        color: #e0e7ff;
        font-size: 1.13rem;
        margin-bottom: 2rem;
        text-shadow: 0 1px 0 #1e40af33;
    }
    .feature-list {
        display: flex;
        flex-wrap: wrap;
        gap: 1.2rem 0.5rem;
        margin-bottom: 1.5rem;
    }
    .feature-item {
        flex: 1 1 45%;
        min-width: 220px;
        display: flex;
        align-items: center;
        background: rgba(255,255,255,0.08);
        border-radius: 18px;
        padding: 0.7rem 1rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 8px rgba(30,64,175,0.08);
        transition: background 0.2s;
    }
    .feature-item:hover {
        background: rgba(255,255,255,0.18);
    }
    .feature-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #fff 60%, #e3e9f7 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.5rem;
        color: #2563eb;
        box-shadow: 0 2px 8px rgba(30,64,175,0.10);
        flex-shrink: 0;
        transition: background 0.2s, color 0.2s;
    }
    .feature-item:hover .feature-icon {
        background: linear-gradient(135deg, #0ea5e9 0%, #fff 100%);
        color: #1e40af;
    }
    .feature-text-content {
        margin-left: 1.1rem;
    }
    .feature-text-content h5 {
        color: #fff;
        font-size: 1.08rem;
        font-weight: 600;
        margin-bottom: 0;
        letter-spacing: 0.01em;
        text-shadow: 0 1px 0 #1e40af33;
    }
    .feature-img-container {
        position: relative;
        height: 100%;
        min-height: 400px;
        border-radius: 0 0 32px 0;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(30,64,175,0.10);
    }
    .feature-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 0 0 32px 0;
        filter: brightness(0.97) contrast(1.04);
        transition: transform 0.3s;
    }
    .feature-img-container img:hover {
        transform: scale(1.04) rotate(-1deg);
    }
    @media (max-width: 991px) {
        .feature-section { border-radius: 18px; left: 0; right: 0; margin-left: 0; margin-right: 0; width: 100vw; max-width: 100vw; }
        .feature-img-container { min-height: 260px; border-radius: 0 0 18px 0; }
        .feature-img-container img { border-radius: 0 0 18px 0; }
    }
    @media (max-width: 767px) {
        .feature-section { border-radius: 10px; left: 0; right: 0; margin-left: 0; margin-right: 0; width: 100vw; max-width: 100vw; }
        .feature-title { font-size: 1.4rem; }
        .feature-img-container { min-height: 180px; border-radius: 0 0 10px 0; }
        .feature-img-container img { border-radius: 0 0 10px 0; }
        .feature-item { min-width: 100%; }
    }
</style>
<div class="feature-section my-5">
    <div class="container-xxl px-0">
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-6 d-flex flex-column justify-content-center py-5 px-4 px-lg-5">
                <h1 class="feature-title">Requisitos para a Inscrição</h1>
                <p class="feature-desc">
                    Para ingressar no Instituto Médio Comercial de Luanda, os candidatos devem atender aos requisitos estabelecidos pela instituição:
                </p>
                <div class="feature-list">
                    <div class="feature-item wow fadeInUp" data-wow-delay="0.1s">
                        <div class="feature-icon">
                            <i class="fa fa-camera"></i>
                        </div>
                        <div class="feature-text-content">
                            <h5>Duas Fotos tipo-passe</h5>
                        </div>
                    </div>
                    <div class="feature-item wow fadeInUp" data-wow-delay="0.2s">
                        <div class="feature-icon">
                            <i class="fa fa-certificate"></i>
                        </div>
                        <div class="feature-text-content">
                            <h5>Certificado de Habilitação</h5>
                        </div>
                    </div>
                    <div class="feature-item wow fadeInUp" data-wow-delay="0.3s">
                        <div class="feature-icon">
                            <i class="fa fa-medkit"></i>
                        </div>
                        <div class="feature-text-content">
                            <h5>Atestado Médico</h5>
                        </div>
                    </div>
                    <div class="feature-item wow fadeInUp" data-wow-delay="0.4s">
                        <div class="feature-icon">
                            <i class="fa fa-id-card"></i>
                        </div>
                        <div class="feature-text-content">
                            <h5>Cópia do BI</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="feature-img-container h-100">
                    <img src="img/cadastro-de-alunos.jpeg" alt="Cadastro de Alunos">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Feature End -->


<!-- Eventos Start -->
<style>
    .event-section {
        background: linear-gradient(120deg, #f8fafc 60%, #e3e9f7 100%);
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(30,64,175,0.10), 0 2px 8px rgba(0,0,0,0.04);
        padding: 3rem 0 2rem 0;
        margin-bottom: 2rem;
    }
    .event-title {
        font-size: 2.3rem;
        font-weight: 800;
        color: #1a2340;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }
    .event-underline {
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
        border-radius: 2px;
        margin: 0 auto 2rem auto;
        display: block;
    }
    .event-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 6px 32px rgba(30,64,175,0.10), 0 1.5px 4px rgba(0,0,0,0.04);
        transition: transform 0.22s cubic-bezier(.4,0,.2,1), box-shadow 0.22s cubic-bezier(.4,0,.2,1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: none;
        position: relative;
        padding: 0;
    }
    .event-card:hover {
        transform: translateY(-10px) scale(1.025);
        box-shadow: 0 12px 40px rgba(30,64,175,0.16), 0 2px 8px rgba(0,0,0,0.06);
    }
    .event-img-container {
        width: 100%;
        height: 200px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        position: relative;
    }
    .event-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    .event-card:hover .event-img-container img {
        transform: scale(1.05);
    }
    .event-body {
        padding: 1.5rem 1.2rem 1.2rem 1.2rem;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
    }
    .event-title-card {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 0.5rem;
        min-height: 48px;
    }
    .event-desc {
        color: #4b5563;
        font-size: 1.01rem;
        margin-bottom: 1.2rem;
        flex: 1 1 auto;
        text-align: justify;
        min-height: 72px;
    }
    .event-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    .event-date-badge {
        background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
        color: #fff;
        border-radius: 30px;
        padding: 0.3rem 1rem;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(30,64,175,0.08);
    }
    .event-btn {
        background: transparent;
        color: #1e40af;
        border: 2px solid #1e40af;
        border-radius: 30px;
        padding: 0.3rem 1.2rem;
        font-weight: 600;
        font-size: 1rem;
        transition: background 0.2s, color 0.2s, border 0.2s;
        box-shadow: 0 2px 8px rgba(30,64,175,0.08);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    .event-btn:hover {
        background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
        color: #fff;
        border: 2px solid #0ea5e9;
        text-decoration: none;
    }
    @media (max-width: 991px) {
        .event-card {
            padding: 0;
        }
        .event-title {
            font-size: 2rem;
        }
    }
    @media (max-width: 767px) {
        .event-section {
            border-radius: 16px;
            padding: 2rem 0 1rem 0;
        }
        .event-title {
            font-size: 1.5rem;
        }
        .event-card {
            padding: 0;
        }
        .event-body {
            padding: 1rem 0.8rem 0.8rem 0.8rem;
        }
    }
</style>
<div class="container-xxl py-5 event-section">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="event-title">Eventos Mais Recentes</h1>
            <span class="event-underline"></span>
        </div>
        <div class="row g-4">
            <?php
            // Conexão com o banco de dados
            $conn = $conexao;

            // Query para buscar os 3 eventos ativos mais recentes
            $sql = "SELECT 
                        id,
                        titulo, 
                        descricao, 
                        data_evento, 
                        url_imagem 
                    FROM 
                        eventos 
                    WHERE 
                        stato = 'Ativo' 
                    ORDER BY 
                        data_evento DESC 
                    LIMIT 3";

            $result = $conn->query($sql);

            // Exibe os dados formatados
            if ($result && $result->num_rows > 0) {
                $delay = 0.1;
                while ($row = $result->fetch_assoc()) {
                    // Define valores padrão se a URL da imagem não for fornecida
                    $imagem = !empty($row['url_imagem']) ? "admin/" . htmlspecialchars($row['url_imagem']) : "img/sem-imagem.jpg";
                    $data_formatada = date('d/m/Y H:i', strtotime($row['data_evento']));
                    $descricao = mb_substr(strip_tags($row['descricao']), 0, 120, 'UTF-8');
                    if (mb_strlen(strip_tags($row['descricao']), 'UTF-8') > 120) {
                        $descricao .= '...';
                    }
                    $titulo = htmlspecialchars($row['titulo']);
                    $id_evento = isset($row['id']) ? (int)$row['id'] : 0;
                    ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?= number_format($delay, 1) ?>s">
                        <div class="event-card">
                            <div class="event-img-container">
                                <img class="img-fluid" src="<?= $imagem ?>" alt="Imagem do evento">
                            </div>
                            <div class="event-body">
                                <h4 class="event-title-card"><?= $titulo ?></h4>
                                <p class="event-desc"><?= htmlspecialchars($descricao) ?></p>
                                <div class="event-footer">
                                    <span class="event-date-badge"><i class="fa fa-calendar"></i> <?= $data_formatada ?></span>
                                    <a class="event-btn" href="detalha_evento?id=<?= $id_evento ?>">
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
                echo '<div class="col-12 text-center"><p>Nenhum evento encontrado.</p></div>';
            }
            ?>
        </div>
    </div>
</div>
<!-- Eventos End -->


<?php
include_once "footer.php";

?>
</body>

</html>