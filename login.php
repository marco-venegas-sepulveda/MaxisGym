<?php
/**
 * Pantalla de Iniciar sesión.
 * Verifica correo y contraseña (hash bcrypt con password_verify) y manda a
 * cada usuario al panel que le corresponde según sus permisos.
 */
require __DIR__ . '/app/bootstrap.php';

// Si ya tiene sesión, no tiene caso mostrar el login
if ($yo = usuario_actual()) {
    redirigir(panel_de($yo));
}

$error = null;

if (es_post()) {
    csrf_verificar();

    $email = mb_strtolower(entrada('email'));
    $password = $_POST['password'] ?? '';
    $recordar = isset($_POST['recordar']);

    $usuario = fila('SELECT id, password, activo FROM usuarios WHERE email = ?', [$email]);

    // Mismo mensaje si el correo no existe o la contraseña está mal:
    // así no se revela qué correos están registrados.
    if ($usuario === null || !is_string($password) || !password_verify($password, $usuario['password'])) {
        $error = 'Correo o contraseña incorrectos.';
    } elseif (!$usuario['activo']) {
        $error = 'Tu cuenta está dada de baja. Acude a recepción para reactivarla.';
    } else {
        // Si el algoritmo de hash cambió, se actualiza el hash guardado
        if (password_needs_rehash($usuario['password'], PASSWORD_DEFAULT)) {
            consulta('UPDATE usuarios SET password = ?, updated_at = NOW() WHERE id = ?',
                [password_hash($password, PASSWORD_DEFAULT), $usuario['id']]);
        }

        iniciar_sesion($usuario, $recordar);

        $destino = $_SESSION['despues_de_login'] ?? '';
        unset($_SESSION['despues_de_login']);
        // Sólo se regresa a rutas internas del propio sitio
        if (is_string($destino) && str_starts_with($destino, base_url() . '/') && !str_contains($destino, '//')) {
            header('Location: ' . $destino);
            exit;
        }
        redirigir(panel_de(cargar_usuario((int) $usuario['id'])));
    }

    guardar_viejo(['email' => $email]);
}

$titulo = 'Iniciar sesión · MaxisGym';
$activo = '';
require ROOT_PATH . '/app/vistas/publico_inicio.php';
?>

<section class="acceso">
    <div class="acceso-panel">
        <h1>Iniciar sesión</h1>
        <p class="acceso-sub">Entra a tu panel con el correo con el que te registraste.</p>

        <?= flash_html() ?>
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= url('login.php') ?>">
            <?= csrf_campo() ?>
            <div class="mb-3">
                <label class="form-label" for="email">Correo</label>
                <input class="form-control form-control-lg" id="email" name="email" type="email"
                       value="<?= viejo('email') ?>" required autocomplete="username" autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Contraseña</label>
                <input class="form-control form-control-lg" id="password" name="password" type="password"
                       required autocomplete="current-password">
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="recordar" name="recordar" value="1">
                    <label class="form-check-label" for="recordar">Recordarme</label>
                </div>
                <a href="<?= url('recuperar.php') ?>">¿Olvidaste tu contraseña?</a>
            </div>
            <button class="btn btn-seguridad btn-lg w-100" type="submit">Iniciar sesión</button>
        </form>

        <p class="acceso-pie">¿No tienes cuenta? <a href="<?= url('registro.php') ?>">Regístrate</a></p>
    </div>
</section>

<?php
limpiar_viejo();
require ROOT_PATH . '/app/vistas/publico_fin.php';
?>
