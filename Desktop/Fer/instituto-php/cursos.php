<?php
$titulo_pagina = 'Cursos y talleres';
$descripcion_pagina = 'Buscador en vivo de cursos y talleres del Instituto Cooperativo 14 de Abril N.º 8001.';
require_once 'includes/header.php';
?>
    <main id="contenido">
      <section class="page-header">
        <div class="container">
          <nav aria-label="Migas de pan">
            <ol class="breadcrumb mb-2">
              <li class="breadcrumb-item"><a href="<?= e(url('index.php')) ?>">Inicio</a></li>
              <li class="breadcrumb-item"><a href="<?= e(url('propuesta-educativa.php')) ?>">Propuesta educativa</a></li>
              <li class="breadcrumb-item active" aria-current="page">Cursos y talleres</li>
            </ol>
          </nav>
          <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
            <div>
              <h1 class="mb-1">Cursos y talleres</h1>
              <p class="lead mb-0">Consultá las propuestas disponibles y sus cupos.</p>
            </div>
            <span class="badge text-bg-success align-self-start">Búsqueda en vivo</span>
          </div>
        </div>
      </section>

      <section class="py-5">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-10">
              <form
                id="formBusqueda"
                class="card course-search-card"
                action="<?= e(url('cursos.php')) ?>"
                method="get"
                role="search"
                data-endpoint="<?= e(url('api/cursos.php')) ?>"
                data-inscripcion="<?= e(url('inscripcion.php')) ?>"
              >
                <div class="card-body p-4 p-md-5">
                  <label for="buscarCurso" class="form-label fw-semibold">Buscar curso o taller</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text" aria-hidden="true">🔍</span>
                    <input
                      type="search"
                      class="form-control"
                      id="buscarCurso"
                      name="q"
                      placeholder="Ej.: web, PHP, programación..."
                      autocomplete="off"
                      aria-describedby="ayudaBusqueda"
                    />
                    <button class="btn btn-primary" type="submit">Buscar</button>
                    <button class="btn btn-outline-secondary" type="button" id="limpiarBusqueda">Limpiar</button>
                  </div>
                  <p id="ayudaBusqueda" class="note mt-3 mb-0">
                    Los resultados se actualizan automáticamente mientras escribís, sin recargar la página.
                  </p>
                </div>
              </form>

              <noscript>
                <div class="alert alert-warning mt-4 mb-0" role="alert">
                  Para usar el buscador en vivo necesitás activar JavaScript. También podés consultar la
                  <a href="<?= e(url('propuesta-educativa.php')) ?>">propuesta educativa</a>.
                </div>
              </noscript>

              <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4 mb-2">
                <h2 class="h4 mb-0">Resultados</h2>
                <p id="estadoCursos" class="note mb-0" aria-live="polite">Cargando cursos...</p>
              </div>

              <div id="resultadosCursos" class="row g-4" aria-busy="true"></div>
            </div>
          </div>
        </div>
      </section>
    </main>
    <script src="<?= e(url('js/cursos.js?v=tp9')) ?>"></script>
<?php require_once 'includes/footer.php'; ?>
