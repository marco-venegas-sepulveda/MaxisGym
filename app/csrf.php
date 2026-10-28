<?php
/**
 * Protección CSRF (Cross-Site Request Forgery).
 *
 * Cada sesión tiene un token aleatorio. Todos los formularios POST lo
 * envían en un campo oculto (csrf_campo()) y las peticiones AJAX en la
 * cabecera X-CSRF-Token. Si el token no coincide, la petición se rechaza.
 * Así otro sitio no puede enviar formularios en nombre del usuario.
 */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Campo oculto para poner dentro de cada <form method="post">. */
function csrf_campo(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/** Verifica el token del formulario o de la cabecera AJAX. */
function csrf_valido(): bool
{
    $enviado = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    return is_string($enviado) && $enviado !== '' && hash_equals(csrf_token(), $enviado);
}

/**
 * Detiene la petición si es POST y el token no es válido.
 * Se llama al inicio de cada página que procesa formularios.
 */
function csrf_verificar(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_valido()) {
        http_response_code(403);
        if (es_ajax()) {
            responder_json(['ok' => false, 'mensaje' => 'La sesión expiró. Recarga la página.'], 403);
        }
        exit('La sesión expiró o el formulario no es válido. Regresa y recarga la página.');
    }
}
