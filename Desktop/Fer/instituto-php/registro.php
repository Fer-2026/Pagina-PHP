<?php
declare(strict_types=1);

require_once __DIR__ . '/config/sesion.php';
require_once __DIR__ . '/config/conexion.php';

if (usuario_autenticado()) {
    redirigir('dashboard.php');
}

$titulo_pagina = 'Crear cuenta';
$errores = [];
$nombre = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        $errores[] = 'La sesión del formulario expiró. Volvé a intentarlo.';
    }

    $nombre = trim((string) ($_POST['nombre'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $password_confirm = (string) ($_POST['password_confirm'] ?? '');

    // Sanitización de entradas: se eliminan etiquetas y se normalizan espacios.
    $nombre = strip_tags($nombre);
    $nombre = preg_replace('/\s+/u', ' ', $nombre) ?? '';
    $email = (string) filter_var($email, FILTER_SANITIZE_EMAIL);

    if ($nombre === '' || mb_strlen($nombre) < 2 || mb_strlen($nombre) > 100) {
        $errores[] = 'El nombre debe tener entre 2 y 100 caracteres.';
    } elseif (preg_match('/^[\p{L}\p{M}\s.\'-]+$/u', $nombre) !== 1) {
        $errores[] = 'El nombre solo puede contener letras, espacios, puntos, apóstrofes y guiones.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Ingresá un correo electrónico válido.';
    } elseif (mb_strlen($email) > 150) {
        $errores[] = 'El correo electrónico no puede superar los 150 caracteres.';
    }

    if (strlen($password) < 8) {
        $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif (preg_match('/[A-Za-z]/', $password) !== 1 || preg_match('/\d/', $password) !== 1) {
        $errores[] = 'La contraseña debe incluir al menos una letra y un número.';
    }

    if ($password !== $password_confirm) {
        $errores[] = 'Las contraseñas no coinciden.';
    }

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);

            if ($stmt->fetch()) {
                $errores[] = 'El correo electrónico ya está registrado.';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare(
                    'INSERT INTO usuarios (nombre, email, password, rol)
                     VALUES (:nombre, :email, :password, :rol)'
                );
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':password' => $hash,
                    ':rol' => 'usuario', // El rol nunca se toma del formulario público.
                ]);

                redirigir('login.php?registro=ok');
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $errores[] = 'El correo electrónico ya está registrado.';
            } else {
                error_log('Error al registrar usuario: ' . $e->getMessage());
                $errores[] = 'No se pudo crear la cuenta. Intentá nuevamente en unos minutos.';
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<main id="contenido">
  <section class="page-header">
    <div class="container">
      <nav aria-label="Migas de pan">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="<?= e(url('index.php')) ?>">Inicio</a></li>
          <li class="breadcrumb-item active" aria-current="page">Crear cuenta</li>
        </ol>
      </nav>
      <h1 class="mb-1">Crear cuenta</h1>
      <p class="mb-0">Registrate para acceder al panel privado del sitio.</p>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">
          <div class="card auth-card">
            <div class="card-body p-4 p-md-5">
              <h2 class="h4 mb-3">Datos de la nueva cuenta</h2>
              <p class="note mb-4">
                La contraseña se guarda cifrada con <code>password_hash()</code> y nunca se muestra en pantalla.
              </p>

              <?php if (!empty($errores)): ?>
                <div class="alert alert-danger" role="alert">
                  <p class="fw-semibold mb-2">Revisá los siguientes puntos:</p>
                  <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                      <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>

              <form method="post" action="<?= e(url('registro.php')) ?>" novalidate>
                <?= csrf_campo() ?>

                <div class="mb-3">
                  <label for="nombre" class="form-label">Nombre y apellido</label>
                  <input
                    type="text"
                    class="form-control"
                    id="nombre"
                    name="nombre"
                    value="<?= e($nombre) ?>"
                    autocomplete="name"
                    minlength="2"
                    maxlength="100"
                    required
                  >
                </div>

                <div class="mb-3">
                  <label for="email" class="form-label">Correo electrónico</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    value="<?= e($email) ?>"
                    autocomplete="email"
                    maxlength="150"
                    required
                  >
                  <div class="form-text">Se usa para iniciar sesión y debe ser único.</div>
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Contraseña</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    minlength="8"
                    required
                  >
                  <div class="form-text">Mínimo 8 caracteres, con al menos una letra y un número.</div>
                </div>

                <div class="mb-4">
                  <label for="password_confirm" class="form-label">Repetir contraseña</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password_confirm"
                    name="password_confirm"
                    autocomplete="new-password"
                    minlength="8"
                    required
                  >
                </div>

                <button type="submit" class="btn btn-accent btn-lg w-100">Crear cuenta</button>
              </form>

              <p class="note text-center mt-4 mb-0">
                ¿Ya tenés cuenta?
                <a href="<?= e(url('login.php')) ?>">Iniciá sesión</a>.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
