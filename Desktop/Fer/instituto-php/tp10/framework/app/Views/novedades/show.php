<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="wrap section detail-section">
    <a class="back-link" href="<?= site_url('novedades') ?>">← Volver a novedades</a>

    <article class="detail-card">
        <p class="eyebrow eyebrow-dark">Novedad institucional</p>
        <h1><?= esc($novedad['titulo']) ?></h1>
        <p class="detail-date">
            Publicada el <time datetime="<?= esc($novedad['fecha'], 'attr') ?>">
                <?= esc(date('d/m/Y', strtotime((string) $novedad['fecha']))) ?>
            </time>
        </p>
        <p class="detail-lead"><?= esc($novedad['resumen']) ?></p>
        <div class="detail-content">
            <?= nl2br(esc($novedad['contenido'])) ?>
        </div>
        <div class="detail-footer">
            <a class="button-link" href="<?= site_url('novedades') ?>">Volver al listado</a>
            <a class="api-link" href="<?= site_url('api/novedades') ?>">Consultar API JSON</a>
        </div>
    </article>
</section>
<?= $this->endSection() ?>
