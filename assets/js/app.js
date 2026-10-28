/**
 * JavaScript común de MaxisGym.
 * Por ahora sólo contiene la función para peticiones AJAX con token CSRF;
 * cada pantalla agrega su propio comportamiento.
 */

/**
 * Hace una petición AJAX (fetch) que envía el token CSRF y espera JSON.
 *   const r = await peticion('api/buscar_socios.php?q=juan');
 *   const r = await peticion('api/pagos.php', { method: 'POST', body: new FormData(form) });
 */
async function peticion(url, opciones = {}) {
    const meta = document.querySelector('meta[name="csrf-token"]');
    const cabeceras = {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        ...(meta ? { 'X-CSRF-Token': meta.content } : {}),
        ...(opciones.headers || {}),
    };
    const respuesta = await fetch(url, { credentials: 'same-origin', ...opciones, headers: cabeceras });
    let datos;
    try {
        datos = await respuesta.json();
    } catch (e) {
        datos = { ok: false, mensaje: 'Respuesta inesperada del servidor.' };
    }
    if (!respuesta.ok && datos.ok === undefined) {
        datos.ok = false;
    }
    return datos;
}
