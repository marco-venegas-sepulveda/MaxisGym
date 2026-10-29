<?php
/**
 * ¿Olvidaste tu contraseña? — Paso 2: crear la nueva contraseña.
 *
 * Llega desde el enlace del correo con ?email=...&token=...
 * Se valida que el token coincida (comparando hashes) y que no tenga más de
 * 30 minutos. Al cambiar la contraseña, el token se borra (un solo uso) y se
 * invalidan las sesiones recordadas en otros equipos.
 */
require __DIR__ . '/app/bootstrap.php';

$email = mb_strtolower(es_post() ? entrada('email') : parametro('email'));
$token = es_post() ? entrada('token') : parametro('token');
$minutos = (int) config('recuperacion_minutos', 30);

/** ¿El token es válido y vigente para ese correo? */
function token_valido(string $email, string $token, int $minutos): bool
{
    if ($email === '' || !preg_match('/^[a-f0-9]{64}$/', $token)) {
        return false;
    }
    $fila = fila(
        'SELECT token FROM password_reset_tokens
          WHERE email = ? AND created_at >= NOW() - INTERVAL ? MINUTE',
        [$email, $minutos]
    );
    return $fila !== null && hash_equals($fila['token'], hash('sha256', $token));
}

$valido = token_valido($email, $token, $minutos);
$error = null;

if (es_post() && $valido) {
    csrf_verificar();
    $password = $_POST['password'] ?? '';
    $confirmacion = $_POST['password_confirmacion'] ?? '';

    $error = (is_string($password) && is_string($confirmacion))
        ? error_password($password, $confirmacion)
        : 'Escribe la nueva contraseña.';

    if ($error === null) {
        db()->beginTransaction();
        consulta(
            'UPDATE usuarios SET password = ?, remember_token = NULL, updated_at = NOW() WHERE email = ?',
            [password_hash($password, PASSWORD_DEFAULT), $email]
        );
        consulta('DELETE FROM password_reset_tokens WHERE email = ?', [$email]);
        db()->commit();

        flash('success', 'Listo, tu contraseña se cambió. Ya puedes iniciar sesión con la nueva.');
        redirigir('login.php');
    }
}

$titulo = 'Nueva contraseña · MaxisGym';
$activo = '';
require ROOT_PATH . '/app/vistas/publico_inicio.php';
?>

<section class="acceso">
    <div class="acceso-panel">
        <?php if (!$valido): ?>
            <h1>Enlace no válido</h1>
            <p class="acceso-sub">
                El enlace ya se usó, venció (dura <?= $minutos ?> minutos) o está incompleto.
                Pide uno nuevo para cambiar tu contraseña.
            </p>
            <a class="btn btn-seguridad btn-lg w-100" href="<?= url('recuperar.php') ?>">Pedir un enlace nuevo</a>
        <?php else: ?>
            <h1>Nueva contraseña</h1>
            <p class="acceso-sub">Crea la nueva contraseña para <strong><?= e($email) ?></strong>.</p>

            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= url('restablecer.php') ?>">
                <?= csrf_campo() ?>
                <input type="hidden" name="email" value="<?= e($email) ?>">
                <input type="hidden" name="token" value="<?= e($token) ?>">
                <div class="mb-3">
                    <label class="form-label" for="password">Nueva contraseña</label>
                    <input class="form-control form-control-lg" id="password" name="password" type="password"
                           minlength="8" required autocomplete="new-password" aria-describedby="ayuda-password" autofocus>
                    <div id="ayuda-password" class="form-text">Mínimo 8 caracteres, con letras y números.</div>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password_confirmacion">Confirmar contraseña</label>
                    <input class="form-control form-control-lg" id="password_confirmacion" name="password_confirmacion"
                           type="password" minlength="8" required autocomplete="new-password">
                </div>
                <button class="btn btn-seguridad btn-lg w-100" type="submit">Guardar contraseña</button>
            </form>
        <?php endif; ?>
        <p class="acceso-pie"><a href="<?= url('login.php') ?>">Volver a iniciar sesión</a></p>
    </div>
</section>

<?php require ROOT_PATH . '/app/vistas/publico_fin.php'; ?>
