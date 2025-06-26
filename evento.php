<!DOCTYPE html>
<html lang="pt">

<head>
    <?php include_once "head.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(120deg, #f8fafc 60%, #e3e9f7 100%);
        }
        .eventos-section {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 32px rgba(30,64,175,0.10), 0 2px 8px rgba(0,0,0,0.04);
            padding: 3.5rem 2.5rem 2.5rem 2.5rem;
            margin: 3rem auto 2.5rem auto;
            max-width: 1200px;
            position: relative;
            overflow: hidden;
        }
        .event-title {
            color: #1e293b;
            margin-bottom: 1.1rem;
            font-size: 2.7rem;
            font-weight: 800;
            letter-spacing: 1px;
            line-height: 1.15;
            text-shadow: 0 2px 8px #e3e9f7;
        }
        .event-underline {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #1e40af 60%, #0ea5e9 100%);
            border-radius: 2px;
            margin-bottom: 2rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .evento-card {
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
        .evento-card:hover {
            transform: translateY(-8px) scale(1.025);
            box-shadow: 0 8px 32px rgba(30,64,175,0.18), 0 4px 16px rgba(0,0,0,0.08);
        }
        .evento-img-container {
            width: 100%;
            height: 220px;
            background: #f8f9fa;
            border-radius: 18px 18px 0 0;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(30,64,175,0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .evento-imagem {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 18px 18px 0 0;
            transition: transform 0.3s;
        }
        .evento-card:hover .evento-imagem {
            transform: scale(1.04);
        }
        .evento-badge {
            position: absolute;
            top: 18px;
            left: 18px;
            background: linear-gradient(90deg, #2563eb 60%, #0ea5e9 100%);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            padding: 7px 18px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #1e40af22;
            z-index: 2;
            letter-spacing: 0.5px;
        }
        .evento-conteudo {
            padding: 1.5rem 1.2rem 1.2rem 1.2rem;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }
        .evento-titulo {
            font-size: 1.35rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.7rem;
            min-height: 48px;
            line-height: 1.2;
        }
        .evento-descricao {
            font-size: 1.05rem;
            color: #374151;
            line-height: 1.7;
            margin-bottom: 1.1rem;
            text-align: justify;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            min-height: 60px;
        }
        .evento-info {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            margin-bottom: 1.1rem;
            font-size: 1.01rem;
            color: #1e40af;
            font-weight: 500;
        }
        .evento-info span {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .evento-info i {
            color: #0ea5e9;
            font-size: 1.1rem;
        }
        .evento-botao {
            display: inline-block;
            padding: 12px 32px;
            background: linear-gradient(90deg, #2563eb 60%, #1e40af 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.08rem;
            box-shadow: 0 2px 8px #1e40af22;
            transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.18s;
            margin-top: auto;
        }
        .evento-botao:hover {
            background: linear-gradient(90deg, #1e40af 60%, #2563eb 100%);
            color: #ffd700;
            box-shadow: 0 4px 16px #1e40af33;
            transform: translateY(-2px) scale(1.03);
        }
        /* Modal customizado */
        .modal-evento .modal-content {
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(30,64,175,0.10), 0 2px 8px rgba(0,0,0,0.04);
        }
        .modal-evento .modal-header {
            background: linear-gradient(90deg, #f8fafc 60%, #e3e9f7 100%);
            border-radius: 18px 18px 0 0;
            border-bottom: none;
        }
        .modal-evento .modal-title {
            color: #1e40af;
            font-weight: 800;
        }
        .modal-evento .modal-body img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px #1e40af22;
        }
        .modal-evento .evento-info {
            margin-bottom: 20px;
            color: #1e40af;
        }
        .modal-evento .evento-descricao {
            text-align: justify;
            line-height: 1.7;
            color: #374151;
            font-size: 1.13rem;
        }
        .modal-evento .modal-footer {
            border-top: none;
            border-radius: 0 0 18px 18px;
        }
        @media (max-width: 991.98px) {
            .evento-card {
                min-height: 440px;
            }
        }
        @media (max-width: 767.98px) {
            .eventos-section {
                padding: 1.2rem 0.5rem 1.5rem 0.5rem;
            }
            .evento-card {
                min-height: 0;
            }
            .evento-img-container {
                height: 160px;
            }
            .evento-titulo {
                font-size: 1.1rem;
                min-height: 0;
            }
            .evento-descricao {
                font-size: 0.97rem;
                min-height: 0;
            }
        }
    </style>
</head>

<body>
<?php
include_once "menu_principal.php";
include_once "admin/models/conexao.php";
?>

<div class="container-xxl py-5">
    <div class="eventos-section">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="event-title">Eventos</h1>
            <span class="event-underline"></span>
        </div>
        <div class="row g-4">
            <?php
            $conn = $conexao;
            $sql = "SELECT * FROM eventos WHERE stato = 'Ativo' ORDER BY data_evento ASC";
            $result = $conn->query($sql);

            $badgeLabels = ['Destaque', 'Imperdível', 'Novo', 'Em breve'];
            $badgeColors = [
                'linear-gradient(90deg, #2563eb 60%, #0ea5e9 100%)',
                'linear-gradient(90deg, #1e40af 60%, #2563eb 100%)',
                'linear-gradient(90deg, #0ea5e9 60%, #2563eb 100%)',
                'linear-gradient(90deg, #f59e42 60%, #fbbf24 100%)'
            ];
            $i = 0;

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data_evento = date('d/m/Y H:i', strtotime($row['data_evento']));
                    $badge = $badgeLabels[$i % count($badgeLabels)];
                    $badgeColor = $badgeColors[$i % count($badgeColors)];
                    $imagem = !empty($row['url_imagem']) ? htmlspecialchars($row['url_imagem']) : "img/sem-imagem.jpg";
                    ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?= number_format(0.1 + ($i * 0.1), 1) ?>s">
                        <div class="evento-card">
                            <div class="evento-img-container">
                                <span class="evento-badge" style="background: <?= $badgeColor ?>;"><?= $badge ?></span>
                                <img src="admin/<?=$imagem ?>" alt="Imagem do Evento" class="evento-imagem">
                            </div>
                            <div class="evento-conteudo">
                                <div class="evento-titulo"><?= htmlspecialchars($row['titulo']) ?></div>
                                <div class="evento-descricao"><?= htmlspecialchars(mb_substr(strip_tags($row['descricao']), 0, 120, 'UTF-8')) ?><?= (mb_strlen(strip_tags($row['descricao']), 'UTF-8') > 120 ? '...' : '') ?></div>
                                <div class="evento-info">
                                    <span><i class="fa fa-calendar"></i> <?= $data_evento ?></span>
                                    <?php if (!empty($row['local'])): ?>
                                        <span><i class="fa fa-map-marker-alt"></i> <?= htmlspecialchars($row['local']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="detalha_evento.php?id=<?= $row['id'] ?>" class="evento-botao">
                                    <i class="fa fa-arrow-right"></i> Saiba Mais
                                </a>
                            </div>
                        </div>

                        <!-- Modal para cada evento -->
                        <div class="modal fade modal-evento" id="eventoModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><?= htmlspecialchars($row['titulo']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="<?= $imagem ?>" alt="Imagem do Evento" class="img-fluid">
                                        <div class="evento-info">
                                            <span><i class="fa fa-calendar"></i> <?= $data_evento ?></span>
                                            <?php if (!empty($row['local'])): ?>
                                                <span><i class="fa fa-map-marker-alt"></i> <?= htmlspecialchars($row['local']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="evento-descricao">
                                            <h6>Descrição do Evento:</h6>
                                            <p><?= nl2br(htmlspecialchars($row['descricao'])) ?></p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $i++;
                }
            } else {
                echo '<div class="col-12 text-center"><p class="text-muted">Nenhum evento encontrado.</p></div>';
            }
            ?>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>

</body>
</html>
