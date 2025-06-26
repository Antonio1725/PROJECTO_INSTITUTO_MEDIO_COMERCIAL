<!DOCTYPE html>
<html lang="pt">

<head>
    <?php
    include_once "head.php";
    ?>
    <style>
        body {
            background: linear-gradient(120deg, #f8fafc 60%, #e3e9f7 100%);
        }
        .about-page-container {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 32px rgba(30,64,175,0.10), 0 2px 8px rgba(0,0,0,0.04);
            padding: 3.5rem 2.5rem;
            margin: 3rem auto;
            max-width: 1200px;
            position: relative;
            overflow: hidden;
        }
        .section-title {
            color: #1e293b;
            margin-bottom: 1.1rem;
            font-size: 2.7rem;
            font-weight: 800;
            letter-spacing: 1px;
            line-height: 1.15;
            text-shadow: 0 2px 8px #e3e9f7;
        }
        .section-underline {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
            border-radius: 2px;
            margin-bottom: 2rem;
            display: block;
        }
        .section-underline.center {
            margin-left: auto;
            margin-right: auto;
        }
        .about-text {
            text-align: justify;
            line-height: 1.8;
            color: #374151;
            font-size: 1.1rem;
        }
        .about-image-layout {
            position: relative;
            min-height: 450px;
        }
        .about-img {
            border-radius: 18px;
            box-shadow: 0 12px 32px rgba(30,64,175,0.13), 0 4px 16px rgba(0,0,0,0.06);
            position: absolute;
        }
        .about-img-1 {
            width: 75%;
            right: 0;
            bottom: 0;
            z-index: 2;
        }
        .about-img-2 {
            width: 55%;
            left: 0;
            top: 10%;
            z-index: 1;
            border: 8px solid #fff;
        }
        .gallery-item {
            overflow: hidden;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(30,64,175,0.08), 0 2px 8px rgba(0,0,0,0.04);
            transition: transform 0.3s, box-shadow 0.3s;
            display: block;
        }
        .gallery-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(30,64,175,0.13), 0 4px 16px rgba(0,0,0,0.06);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>
<?php
include_once "menu_principal.php";
?>

<div class="container-fluid py-5">
    <div class="container about-page-container">
        <!-- About Start -->
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="about-image-layout d-none d-lg-block">
                    <img class="img-fluid about-img about-img-1" src="img/about.jpg" alt="Escola Comercial de Luanda">
                    <img class="img-fluid about-img about-img-2" src="img/about.jpg" alt="Estudantes da Escola">
                </div>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <h1 class="section-title">Quem Nós Somos</h1>
                <span class="section-underline"></span>
                <p class="about-text">
                    A Escola Comercial de Luanda é um projeto inovador dedicado à formação de profissionais na área de comércio, localizado na pulsante capital de Angola. Com o objetivo de desenvolver competências essenciais, a escola oferece um currículo abrangente que inclui disciplinas como gestão, marketing, contabilidade e empreendedorismo, preparando seus alunos para as crescentes demandas do mercado. Em um momento em que Angola se empenha em diversificar sua economia, tradicionalmente dependente do petróleo, a Escola Comercial se destaca como uma peça fundamental na transformação do sistema educacional do país. Junto a outras instituições, como o Instituto Médio de Economia de Luanda e o Instituto Politécnico Industrial de Luanda, a Escola Comercial de Luanda forma um ambiente propício para o aprendizado e inovação, proporcionando aos jovens as ferramentas necessárias para se tornarem agentes de mudança e contribuírem para um futuro mais próspero e sustentável.
                </p>
            </div>
        </div>
        <!-- About End -->

        <!-- Gallery Start -->
        <div class="container pt-5 mt-5">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="section-title">Galeria de Funcionários</h1>
                <span class="section-underline center"></span>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="gallery-item">
                        <img src="img/IMG_20250128_104838.jpg" class="img-fluid" alt="Funcionário 1">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="gallery-item">
                        <img src="img/IMG_20250128_104934.jpg" class="img-fluid" alt="Funcionário 2">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="gallery-item">
                        <img src="img/IMG_20250128_104941.jpg" class="img-fluid" alt="Funcionário 3">
                    </div>
                </div>
            </div>
        </div>
        <!-- Gallery End -->
    </div>
</div>

<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>

<?php
include_once "footer.php";
?>
</body>

</html>
