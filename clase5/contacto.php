<?php
require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/includes/formularios.php';

$asuntos = [
    'inscripcion' => 'Consulta de inscripción',
    'becas' => 'Becas',
    'general' => 'Consulta general',
    'otro' => 'Otro',
];
$datos = ['nombre' => '', 'email' => '', 'telefono' => '', 'asunto' => '', 'mensaje' => ''];
$errores = [];

// Mensaje de éxito (patrón Post/Redirect/Get: evita reenviar el formulario al recargar).
$exito = $_SESSION['flash_contacto'] ?? null;
unset($_SESSION['flash_contacto']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Sanitización de TODAS las entradas antes de usarlas.
    $datos['nombre'] = limpiar(filter_input(INPUT_POST, 'nombre', FILTER_DEFAULT), 80);
    $datos['email'] = limpiar(filter_input(INPUT_POST, 'email', FILTER_DEFAULT), 120);
    $datos['telefono'] = limpiar(filter_input(INPUT_POST, 'telefono', FILTER_DEFAULT), 20);
    $datos['asunto'] = limpiar(filter_input(INPUT_POST, 'asunto', FILTER_DEFAULT), 20);
    $datos['mensaje'] = limpiar(filter_input(INPUT_POST, 'mensaje', FILTER_DEFAULT), 1000, true);
    $token = filter_input(INPUT_POST, 'csrf_token', FILTER_DEFAULT);

    // 2) Validación en el servidor.
    if (!csrf_verificar($token)) {
        $errores['general'] = 'La sesión expiró o el envío no es válido. Volvé a intentarlo.';
    }
    if (empty($datos['nombre'])) {
        $errores['nombre'] = 'El nombre es obligatorio.';
    } elseif (mb_strlen($datos['nombre']) < 3) {
        $errores['nombre'] = 'El nombre debe tener al menos 3 caracteres.';
    } elseif (!preg_match('/^[\p{L}\p{M}\s.\'-]+$/u', $datos['nombre'])) {
        $errores['nombre'] = 'El nombre solo puede tener letras, espacios, puntos, apóstrofes y guiones.';
    }
    if (empty($datos['email'])) {
        $errores['email'] = 'El correo electrónico es obligatorio.';
    } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'Ingresá un correo válido, por ejemplo nombre@dominio.com.';
    }
    if ($datos['telefono'] !== '' && !preg_match('/^[0-9+()\s-]{6,20}$/', $datos['telefono'])) {
        $errores['telefono'] = 'El teléfono solo puede tener números, espacios, +, - y paréntesis (6 a 20 caracteres).';
    }
    if (!array_key_exists($datos['asunto'], $asuntos)) {
        $errores['asunto'] = 'Elegí un asunto de la lista.';
    }
    if (empty($datos['mensaje'])) {
        $errores['mensaje'] = 'El mensaje es obligatorio.';
    } elseif (mb_strlen($datos['mensaje']) < 10) {
        $errores['mensaje'] = 'El mensaje debe tener al menos 10 caracteres.';
    }

    // 3) Sin errores: se guarda el resumen y se redirige. Con errores: se conservan los datos.
    if (!$errores) {
        $_SESSION['flash_contacto'] = ['nombre' => $datos['nombre'], 'email' => $datos['email'], 'asunto' => $asuntos[$datos['asunto']], 'mensaje' => $datos['mensaje']];
        unset($_SESSION['csrf']);
        header('Location: contacto.php#resultado');
        exit;
    }
}

$titulo_pagina = 'Contacto';
require_once 'includes/header.php';
?>
    <main id="contenido">
<section class="page-header"><div class="container"><nav aria-label="Ruta"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php">Inicio</a></li><li class="breadcrumb-item active" aria-current="page">Contacto</li></ol></nav><h1>Contacto</h1><p class="lead mb-0">Estamos en Salta capital.</p></div></section><section class="py-5"><div class="container"><h2 class="section-title">Datos de contacto</h2><div class="row g-4"><div class="col-md-6"><ul class="list-unstyled"><li><strong>Dirección:</strong> Caseros 1978, Salta (CP 4400)</li><li><strong>Turno:</strong> mañana</li><li><strong>Teléfonos:</strong> <a href="tel:+543874320675">(0387) 432-0675</a> · <a href="tel:+543875918006">387-591-8006</a></li><li><strong>Correo:</strong> <a href="mailto:<?= e($email_contacto) ?>"><?= e($email_contacto) ?></a></li><li><strong>Cooperativa:</strong> <a href="mailto:cooperativamerica14@gmail.com">cooperativamerica14@gmail.com</a></li></ul><p class="note">Datos tomados de documentos públicos del instituto: confirmá que sigan vigentes.</p></div><div class="col-md-6"><h3 class="h5">Redes sociales</h3><ul><li><a href="https://share.google/0eQSeQQhdelOzPzhW" rel="noopener" target="_blank">Facebook<span class="visually-hidden"> (se abre en otra pestaña)</span></a></li><li><a href="https://www.instagram.com/colegio14deabril/" rel="noopener" target="_blank">Instagram @colegio14deabril<span class="visually-hidden"> (se abre en otra pestaña)</span></a></li></ul></div></div></div></section>
      <section class="py-5" aria-labelledby="titulo-form">
        <div class="container">
          <h2 class="section-title" id="titulo-form">Escribinos</h2>

          <?php if ($exito): ?>
            <div class="alert alert-success" id="resultado" role="status">
              <strong>¡Gracias, <?= e($exito['nombre']) ?>!</strong> Recibimos tu consulta sobre “<?= e($exito['asunto']) ?>”.
              Te responderemos a <strong><?= e($exito['email']) ?></strong>.
              <p class="mb-0 mt-2 small">Tu mensaje: <?= nl2br(e($exito['mensaje'])) ?></p>
            </div>
          <?php endif; ?>

          <?php if ($errores): ?>
            <div class="alert alert-danger" role="alert">
              <strong>Revisá el formulario:</strong>
              <ul class="mb-0">
                <?php foreach ($errores as $mensaje_error): ?>
                  <li><?= e($mensaje_error) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <!-- Formulario POST: novalidate para que la validación la haga el servidor -->
          <form class="row g-3" action="contacto.php" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>" />
            <div class="col-md-6">
              <label class="form-label" for="nombre">Nombre y apellido <span class="text-danger" aria-hidden="true">*</span></label>
              <input class="form-control<?= clase_invalida($errores, 'nombre') ?>" id="nombre" name="nombre" type="text" maxlength="80" autocomplete="name" aria-required="true" value="<?= e($datos['nombre']) ?>"<?= atributos_error($errores, 'nombre') ?> />
              <?php if (isset($errores['nombre'])): ?><div class="invalid-feedback d-block" id="error-nombre"><?= e($errores['nombre']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="email">Correo electrónico <span class="text-danger" aria-hidden="true">*</span></label>
              <input class="form-control<?= clase_invalida($errores, 'email') ?>" id="email" name="email" type="email" maxlength="120" autocomplete="email" aria-required="true" value="<?= e($datos['email']) ?>"<?= atributos_error($errores, 'email') ?> />
              <?php if (isset($errores['email'])): ?><div class="invalid-feedback d-block" id="error-email"><?= e($errores['email']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="telefono">Teléfono (opcional)</label>
              <input class="form-control<?= clase_invalida($errores, 'telefono') ?>" id="telefono" name="telefono" type="tel" maxlength="20" autocomplete="tel" value="<?= e($datos['telefono']) ?>"<?= atributos_error($errores, 'telefono') ?> />
              <?php if (isset($errores['telefono'])): ?><div class="invalid-feedback d-block" id="error-telefono"><?= e($errores['telefono']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="asunto">Asunto <span class="text-danger" aria-hidden="true">*</span></label>
              <select class="form-select<?= clase_invalida($errores, 'asunto') ?>" id="asunto" name="asunto" aria-required="true"<?= atributos_error($errores, 'asunto') ?>>
                <option value="">Elegí una opción</option>
                <?php foreach ($asuntos as $clave => $texto_asunto): ?>
                  <option value="<?= e($clave) ?>"<?= $datos['asunto'] === $clave ? ' selected' : '' ?>><?= e($texto_asunto) ?></option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errores['asunto'])): ?><div class="invalid-feedback d-block" id="error-asunto"><?= e($errores['asunto']) ?></div><?php endif; ?>
            </div>
            <div class="col-12">
              <label class="form-label" for="mensaje">Mensaje <span class="text-danger" aria-hidden="true">*</span></label>
              <textarea class="form-control<?= clase_invalida($errores, 'mensaje') ?>" id="mensaje" name="mensaje" rows="5" maxlength="1000" aria-required="true"<?= atributos_error($errores, 'mensaje') ?>><?= e($datos['mensaje']) ?></textarea>
              <?php if (isset($errores['mensaje'])): ?><div class="invalid-feedback d-block" id="error-mensaje"><?= e($errores['mensaje']) ?></div><?php endif; ?>
            </div>
            <div class="col-12"><button class="btn btn-accent btn-lg" type="submit">Enviar consulta</button></div>
          </form>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
