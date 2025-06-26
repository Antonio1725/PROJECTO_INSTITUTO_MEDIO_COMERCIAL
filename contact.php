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

        .contact-page-container {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 32px rgba(30, 64, 175, 0.10), 0 2px 8px rgba(0, 0, 0, 0.04);
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

        .contact-info-card {
            background: linear-gradient(120deg, #f8fafc 60%, #e3e9f7 100%);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            height: 100%;
            box-shadow: 0 4px 24px rgba(30,64,175,0.08), 0 2px 8px rgba(0,0,0,0.04);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .contact-info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(30,64,175,0.13), 0 4px 16px rgba(0,0,0,0.06);
        }

        .contact-info-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 1.5rem auto;
            background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 4px 16px rgba(30,64,175,0.2);
        }

        .contact-info-card h5 {
            color: #1e40af;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .contact-info-card p {
            color: #374151;
            font-size: 1.05rem;
            line-height: 1.6;
            min-height: 70px;
        }

        .contact-form-section {
            background: linear-gradient(120deg, #f8fafc 60%, #e3e9f7 100%);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(30,64,175,0.08), 0 2px 8px rgba(0,0,0,0.04);
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #d1d5db;
            padding: 0.85rem 1rem;
        }
        .form-control:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 0.25rem rgba(30, 64, 175, 0.2);
        }
        .btn-primary-gradient {
            background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
            border: none;
            color: white;
            padding: 0.85rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30,64,175,0.25);
        }

        .map-container {
            border-radius: 20px;
            overflow: hidden;
            height: 100%;
            min-height: 450px;
            box-shadow: 0 4px 24px rgba(30,64,175,0.08), 0 2px 8px rgba(0,0,0,0.04);
        }
    </style>
</head>

<body>
    <?php
    include_once "menu_principal.php";
    ?>

    <div class="container-fluid py-5">
        <div class="container contact-page-container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="section-title">Contacte-nos</h1>
                <span class="section-underline center"></span>
                <p class="mb-4" style="color: #374151;">Tem alguma questão ou sugestão? Adoraríamos ouvir de si. Entre em contacto através dos nossos canais ou preencha o formulário abaixo.</p>
            </div>

            <!-- Contact Info Start -->
            <div class="row g-4 mb-5 justify-content-center">
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fa fa-map-marker-alt"></i>
                        </div>
                        <h5>Endereço</h5>
                        <p>Avenida Deolinda Rodrigues, Instituto Médio Comercial de Luanda (IMCL)</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fa fa-phone-alt"></i>
                        </div>
                        <h5>Telefone</h5>
                        <p>+244 942 979 525</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <h5>Email</h5>
                        <p>geral@escomercial-luanda.ao</p>
                    </div>
                </div>
            </div>
            <!-- Contact Info End -->

            <!-- Form and Map Start -->
            <div class="row g-5">
                
                <div class="col-lg-12 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="map-container">
                        <iframe class="w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3942.52317851495!2d13.24351430117524!3d-8.828926056231623!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1a51f24b8855c597%3A0x154f3a2c6137346a!2sEscola%20Comercial%20de%20Luanda!5e0!3m2!1spt-PT!2spt!4v1716486551439!5m2!1spt-PT!2spt"
                        frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>
                    </div>
                </div>
            </div>
            <!-- Form and Map End -->

        </div>
    </div>

    <?php
    include_once "footer.php";
    ?>
</body>

</html>
