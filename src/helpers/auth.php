<?php
declare(strict_types=1);

require_once __DIR__ . '/response.php';

/**
 * Inicia la sesión si aún no está activa.
 */
function start_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Valida que el usuario esté autenticado.
 */
function require_login(): void {
    start_session();
    if (!isset($_SESSION['usuario_id'])) {
        fail("Acceso denegado: sesión no iniciada", 401);
    }
}

/**
 * Valida que el usuario tenga uno de los roles permitidos.
 * @param array|string $roles - Rol(es) permitido(s): 'admin', 'empleado', 'cliente'
 */
function require_role($roles): void {
    start_session();

    if (!isset($_SESSION['rol'])) {
        fail("Acceso denegado: rol no definido", 401);
    }

    $rol_actual = $_SESSION['rol'];

    if (is_array($roles)) {
        if (!in_array($rol_actual, $roles, true)) {
            fail("Acceso denegado: requiere rol " . implode(', ', $roles), 403);
        }
    } else {
        if ($rol_actual !== $roles) {
            fail("Acceso denegado: requiere rol $roles", 403);
        }
    }
}

/**
 * Retorna el ID de usuario autenticado
 */
function current_user_id(): ?int {
    start_session();
    return $_SESSION['usuario_id'] ?? null;
}

/**
 * Retorna el rol actual
 */
function current_user_role(): ?string {
    start_session();
    return $_SESSION['rol'] ?? null;
}