<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="hero">
    <div class="wrap hero-inner">
        <p class="eyebrow">Módulo independiente · CodeIgniter 4</p>
        <h1>Novedades del Instituto</h1>
        <p class="hero-copy">
            Noticias, talleres y actividades de la comunidad educativa.
            Este módulo se desarrolló con rutas, controlador, modelo y vistas propias del framework.
        </p>
        <div class="hero-tags" aria-label="Características del módulo">
            <span>Query Builder</span>
            <span>Vistas con layout</span>
            <span>API JSON</span>
        </div>
    </div>
</section>

<section class="wrap section">
    <div class="section-heading">
        <div>
            <p class="eyebrow eyebrow-dark">Actualidad institucional</p>
            <h2><?= $busqueda === '' ? 'Últimas novedades' : 'Resultados de búsqueda' ?></h2>
        </div>
        <p class="result-count"><?= count($novedades) ?> <?= count($novedades) === 1 ? 'novedad' : 'novedades' ?></p>
    </div>

    <form class="search-card" action="<?= site_url('novedades') ?>" method="get" role="search">
        <label for="q">Buscar novedades</label>
        <div class="search-controls">
            <input
                type="search"
                id="q"
                name="q"
                value="<?= esc($busqueda, 'attr') ?>"
                placeholder="Título, resumen o contenido..."
            >
            <button type="submit">Buscar</button>
            <?php if ($busqueda !== ''): ?>
                <a class="clear-search" href="<?= site_url('novedades') ?>">Limpiar</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($busqueda !== ''): ?>
        <p class="search-note">
            Mostrando resultados para <strong>“<?= esc($busqueda) ?>”</strong>.
        </p>
    <?php endif; ?>

    <?php if ($novedades === []): ?>
        <div class="empty-state">
            <h2>No encontramos novedades</h2>
            <p>Probá con otra palabra o volvé al listado completo.</p>
            <a class="button-link" href="<?= site_url('novedades') ?>">Ver todas las novedades</a>
        </div>
    <?php else: ?>
        <div class="news-grid">
            <?php foreach ($novedades as $novedad): ?>
                <article class="news-card">
                    <div class="news-meta">
                        <time datetime="<?= esc($novedad['fecha'], 'attr') ?>">
                            <?= esc(date('d/m/Y', strtotime((string) $novedad['fecha']))) ?>
                        </time>
                        <span class="pill">Novedad</span>
                    </div>
                    <h2>
                        <a href="<?= site_url('novedades/' . $novedad['id']) ?>">
                            <?= esc($novedad['titulo']) ?>
                        </a>
                    </h2>
                    <p><?= esc($novedad['resumen']) ?></p>
                    <a class="read-more" href="<?= site_url('novedades/' . $novedad['id']) ?>">
                        Leer novedad <span aria-hidden="true">→</span>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
