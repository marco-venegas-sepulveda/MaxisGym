<?php
/**
 * Avisos del sistema (tabla anuncios).
 *
 * Un aviso es UNA sola fila, dirigida a un rol (o a todos si rol_destino_id
 * es NULL). No se copia a cada usuario: cada quien lo consulta según su rol.
 * Para saber cuáles son nuevos, cada usuario guarda una sola fecha:
 * usuarios.anuncios_vistos_hasta.
 */

/** Condición SQL de avisos visibles para un rol y vigentes en este momento. */
const SQL_AVISO_VISIBLE = '(a.rol_destino_id IS NULL OR a.rol_destino_id = :rol)
    AND a.fecha_publicacion <= NOW()
    AND (a.fecha_expiracion IS NULL OR a.fecha_expiracion > NOW())';

/** Avisos vigentes para el usuario (importantes primero, luego los más recientes). */
function avisos_para(array $usuario, int $limite = 50): array
{
    $st = db()->prepare(
        'SELECT a.*, u.nombre AS autor_nombre, u.apellido AS autor_apellido, r.nombre AS rol_destino
           FROM anuncios a
           JOIN usuarios u ON u.id = a.usuario_id
           LEFT JOIN roles r ON r.id = a.rol_destino_id
          WHERE ' . SQL_AVISO_VISIBLE . '
          ORDER BY a.importante DESC, a.fecha_publicacion DESC
          LIMIT ' . (int) $limite
    );
    $st->execute([':rol' => $usuario['rol_id']]);
    return $st->fetchAll();
}

/** Cuántos avisos se publicaron después de la última vez que el usuario los revisó. */
function avisos_sin_leer(array $usuario): int
{
    $st = db()->prepare(
        'SELECT COUNT(*) FROM anuncios a
          WHERE ' . SQL_AVISO_VISIBLE . '
            AND a.fecha_publicacion > :vistos'
    );
    $st->execute([
        ':rol'    => $usuario['rol_id'],
        ':vistos' => $usuario['anuncios_vistos_hasta'] ?? '1970-01-01 00:00:00',
    ]);
    return (int) $st->fetchColumn();
}

/** Marca todos los avisos como vistos (se llama al abrir la pantalla de avisos). */
function marcar_avisos_vistos(int $usuarioId): void
{
    consulta('UPDATE usuarios SET anuncios_vistos_hasta = NOW() WHERE id = ?', [$usuarioId]);
}
