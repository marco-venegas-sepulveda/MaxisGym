<?php
/**
 * Conexión a MySQL con PDO.
 *
 * Todas las consultas del sistema usan sentencias preparadas
 * (los datos van separados del SQL con ? o :nombre), lo que evita la
 * inyección SQL.
 */

/**
 * Devuelve la conexión (se crea una sola vez por petición).
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $c = config('db');
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $c['host'], $c['port'], $c['database']
        );

        try {
            $pdo = new PDO($dsn, $c['username'], $c['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errores como excepciones
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // filas como arreglos asociativos
                PDO::ATTR_EMULATE_PREPARES   => false,                  // preparadas reales en MySQL
            ]);
        } catch (PDOException $ex) {
            http_response_code(500);
            $detalle = config('debug') ? '<br><small>' . e($ex->getMessage()) . '</small>' : '';
            exit('No se pudo conectar a la base de datos. Revisa que MySQL esté encendido '
                . 'y que exista la base "' . e($c['database']) . '".' . $detalle);
        }

        // Misma zona horaria en MySQL que en PHP (NOW(), CURDATE()...)
        $pdo->exec("SET time_zone = '" . date('P') . "'");
    }

    return $pdo;
}

/**
 * Ejecuta una consulta preparada y devuelve el PDOStatement.
 *   consulta('SELECT * FROM planes WHERE id = ?', [$id])
 */
function consulta(string $sql, array $params = []): PDOStatement
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/** Devuelve todas las filas. */
function filas(string $sql, array $params = []): array
{
    return consulta($sql, $params)->fetchAll();
}

/** Devuelve una sola fila o null. */
function fila(string $sql, array $params = []): ?array
{
    $f = consulta($sql, $params)->fetch();
    return $f === false ? null : $f;
}

/** Devuelve el primer valor de la primera fila (útil para COUNT, SUM...). */
function valor(string $sql, array $params = [])
{
    $v = consulta($sql, $params)->fetchColumn();
    return $v === false ? null : $v;
}

/**
 * ¿La excepción es por una llave foránea que impide borrar? (error 1451 de MySQL)
 * Se usa para dar de baja en lugar de eliminar cuando hay historial.
 */
function es_error_llave_foranea(PDOException $ex): bool
{
    return isset($ex->errorInfo[1]) && (int) $ex->errorInfo[1] === 1451;
}

/** ¿La excepción es por un valor duplicado en un campo UNIQUE? (error 1062) */
function es_error_duplicado(PDOException $ex): bool
{
    return isset($ex->errorInfo[1]) && (int) $ex->errorInfo[1] === 1062;
}
