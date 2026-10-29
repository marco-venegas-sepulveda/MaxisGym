<?php
/**
 * Envío de correos.
 *
 * Con 'mail.driver' => 'archivo' (valor por defecto) cada correo se guarda
 * como página HTML en storage/correos/ para poder abrirlo en el navegador
 * durante las pruebas, sin configurar un servidor de correo.
 * Con 'mail.driver' => 'mail' se usa la función mail() de PHP.
 */

/**
 * Envía un correo HTML. Devuelve true si se envió (o se guardó) correctamente.
 */
function enviar_correo(string $para, string $asunto, string $contenidoHtml): bool
{
    $html = plantilla_correo($asunto, $contenidoHtml);
    $driver = config('mail.driver', 'archivo');

    if ($driver === 'mail') {
        $remitente = sprintf('=?UTF-8?B?%s?= <%s>', base64_encode(config('mail.from_name')), config('mail.from_email'));
        $cabeceras = implode("\r\n", [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $remitente,
        ]);
        return mail($para, '=?UTF-8?B?' . base64_encode($asunto) . '?=', $html, $cabeceras);
    }

    // Driver "archivo": guarda el correo en storage/correos/
    $carpeta = ROOT_PATH . '/storage/correos';
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0775, true);
    }
    $nombre = date('Y-m-d_His') . '_' . preg_replace('/[^a-z0-9]+/i', '-', $para) . '_' . bin2hex(random_bytes(3)) . '.html';
    $encabezado = '<div style="font:13px/1.5 monospace;background:#fff8d6;border-bottom:1px solid #e6d58a;padding:10px 16px">'
        . '<b>Correo de prueba (no se envió)</b><br>'
        . 'Para: ' . e($para) . '<br>'
        . 'De: ' . e(config('mail.from_name') . ' <' . config('mail.from_email') . '>') . '<br>'
        . 'Asunto: ' . e($asunto) . '<br>'
        . 'Fecha: ' . date('d/m/Y H:i:s') . '</div>';

    $html = str_replace('<body style="margin:0;background:#F4F5F3">', '<body style="margin:0;background:#F4F5F3">' . $encabezado, $html);
    return file_put_contents($carpeta . '/' . $nombre, $html) !== false;
}

/** Diseño común de los correos (estilos en línea para que se vean en cualquier cliente). */
function plantilla_correo(string $titulo, string $contenidoHtml): string
{
    $g = config('gimnasio');
    return '<!doctype html><html lang="es"><head><meta charset="utf-8"><title>' . e($titulo) . '</title></head>'
        . '<body style="margin:0;background:#F4F5F3">'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F4F5F3;padding:24px 0">'
        . '<tr><td align="center">'
        . '<table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden;font-family:Arial,Helvetica,sans-serif;color:#1D2329">'
        . '<tr><td style="background:#262C33;padding:18px 28px;border-bottom:4px solid #F2B705">'
        . '<span style="color:#ffffff;font-size:22px;font-weight:bold">MaxisGym</span></td></tr>'
        . '<tr><td style="padding:28px;font-size:15px;line-height:1.6">' . $contenidoHtml . '</td></tr>'
        . '<tr><td style="padding:16px 28px;background:#F4F5F3;color:#6b747d;font-size:12px;line-height:1.5">'
        . e($g['direccion']) . '<br>' . e($g['telefono']) . ' · ' . e($g['email']) . '</td></tr>'
        . '</table></td></tr></table></body></html>';
}

/** Botón para correos. */
function boton_correo(string $texto, string $enlace): string
{
    return '<p style="margin:24px 0"><a href="' . e($enlace) . '" style="background:#F2B705;color:#1D2329;'
        . 'text-decoration:none;font-weight:bold;padding:12px 22px;border-radius:6px;display:inline-block">'
        . e($texto) . '</a></p>';
}

/** URL completa (con http://servidor) para poner en correos. */
function url_absoluta(string $ruta): string
{
    // Si está configurada la URL pública, se usa ésa (evita que alguien
    // falsifique el dominio enviando otra cabecera Host).
    $publica = config('url_publica');
    if ($publica) {
        return rtrim($publica, '/') . '/' . ltrim($ruta, '/');
    }
    // Desde la terminal (tareas programadas) no hay petición web: se supone Laragon
    if (PHP_SAPI === 'cli') {
        return 'http://localhost/' . basename(ROOT_PATH) . '/' . ltrim($ruta, '/');
    }
    $esquema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $esquema . '://' . $host . url($ruta);
}
