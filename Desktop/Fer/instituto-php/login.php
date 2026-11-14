<?php
declare(strict_types=1);

require_once __DIR__ . '/config/sesion.php';
require_once __DIR__ . '/config/conexion.php';

// La sesión ya se inicia en config/sesion.php; se deja el chequeo explícito.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (usuario_autenticado()) {
    redirigir(es_admin() ? 'admin_panel.php' : 'dashboard.php');
}

$titulo_pagina = 'Ingresar';
$errores = [];
$email = '';

$aviso = null;
$avisoTipo = 'info';

if (isset($_GET['registro']) && $_GET['registro'] === 'ok') {
    $aviso = 'Cuenta creada correctamente. Ya podés iniciar sesión.';
    $avisoTipo = 'success';
} elseif (isset($_GET['logout']) && $_GET['logout'] === 'ok') {
    $aviso = 'Cerraste sesión correctamente.';
    $avisoTipo = 'success';
} elseif (isset($_GET['error']) && $_GET['error'] === 'acceso_denegado') {
    $aviso = 'Tenés que iniciar sesión para acceder a esa página.';
    $avisoTipo = 'warning';
} elseif (isset($_GET['error']) && $_GET['error'] === 'sin_permiso') {
    $aviso = 'Tu cuenta no tiene permisos para acceder al panel de administración.';
    $avisoTipo = 'danger';
} elseif (isset($_GET['error']) && $_GET['error'] === 'sesion_invalida') {
    $aviso = 'La sesión dejó de ser válida. Volvé a ingresar.';
    $avisoTipo = 'warning';
}

// Hash de referencia para mantener un tiempo de respuesta similar cuando el correo no existe.
$hashReferencia = '$2y$10$0vpHNT1MQiwWM0LLodvCy.hzzoLzvpueTr2d64LsfEgtGJChSkYEG';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        $errores[] = 'La sesión del formulario expiró. Volvé a intentarlo.';
    }

    $email = (string) filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_SANITIZE_EMAIL);
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Ingresá un correo electrónico válido.';
    }

    if ($password === '') {
        $errores[] = 'Ingresá tu contraseña.';
    }

    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare(
                'SELECT id, nombre, email, password, rol
                 FROM usuarios
                 WHERE email = :email
                 LIMIT 1'
            );
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            $hash = $usuario['password'] ?? $hashReferencia;
            $credencialesValidas = password_verify($password, $hash);

            if (!$usuario || !$credencialesValidas) {
                $errores[] = 'Correo o contraseña incorrectos.';
            } else {
                // Mitiga la fijación de sesión: se descarta el ID anterior.
                session_regenerate_id(true);

                $_SESSION['usuario_id'] = (int) $usuario['id'];
                $_SESSION['usuario_nombre'] = (string) $usuario['nombre'];
                $_SESSION['rol'] = (string) $usuario['rol'];
                $_SESSION['usuario_email'] = (string) $usuario['email'];
                $_SESSION['ultimo_login'] = date('Y-m-d H:i:s');
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                if ($usuario['rol'] === 'admin') {
                    redirigir('admin_panel.php?bienvenida=1');
                }

                redirigir('dashboard.php?bienvenida=1');
            }
        } catch (PDOException $e) {
            error_log('Error al iniciar sesión: ' . $e->getMessage());
            $errores[] = 'No se pudo iniciar sesión. Intentá nuevamente.';
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
          <li class="breadcrumb-item active" aria-current="page">Ingresar</li>
        </ol>
      </nav>
      <h1 class="mb-1">Ingresar</h1>
      <p class="mb-0">Accedé con la cuenta que creaste en el sitio.</p>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-xl-4">
          <div class="card auth-card">
            <div class="card-body p-4 p-md-5">
              <h2 class="h4 mb-3">Inicio de sesión</h2>

              <?php if ($aviso !== null): ?>
                <div class="alert alert-<?= e($avisoTipo) ?>" role="alert">
                  <?= e($aviso) ?>
                </div>
              <?php endif; ?>

              <?php if (!empty($errores)): ?>
                <div class="alert alert-danger" role="alert">
                  <?php foreach ($errores as $error): ?>
                    <p class="mb-0"><?= e($error) ?></p>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <form method="post" action="<?= e(url('login.php')) ?>" novalidate>
                <?= csrf_campo() ?>

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
                </div>

                <div class="mb-4">
                  <label for="password" class="form-label">Contraseña</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                  >
                </div>

                <button type="submit" class="btn btn-accent btn-lg w-100">Ingresar</button>
              </form>

              <p class="note text-center mt-4 mb-0">
                ¿Todavía no tenés cuenta?
                <a href="<?= e(url('registro.php')) ?>">Crear una cuenta</a>.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
