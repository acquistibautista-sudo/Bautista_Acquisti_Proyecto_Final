<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit;
}

try {
    $sql = "SELECT id, nombre, apellido, dni, email, rol, archivado_en 
            FROM usuarios 
            WHERE archivado_en IS NOT NULL 
            ORDER BY id DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $error = "Error al obtener la lista de usuarios archivados.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Urban Style - Usuarios Archivados</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div style="max-width: 1100px; margin: 2rem auto; padding: 0 1rem;">
        <div style="background: #ffffff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Panel de Administración - Usuarios Archivados</h2>
                <div>
                    <a href="admin_usuarios.php" style="margin-right: 1rem; color: #2F7D4F; text-decoration: none; font-weight: 500;">Ver Activos</a>
                    <a href="cerrar_sesion.php" style="background-color: #B3261E; color: #fff; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none;">Cerrar Sesión</a>
                </div>
            </div>

            <?php if (!empty($error)): ?>
                <div style="color: #B3261E; margin-bottom: 1rem;"><p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p></div>
            <?php endif; ?>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                    <thead>
                        <tr style="background: #F5F3EE; border-bottom: 2px solid #D9D6CF;">
                            <th style="padding: 0.75rem;">ID</th>
                            <th style="padding: 0.75rem;">Nombre y Apellido</th>
                            <th style="padding: 0.75rem;">DNI</th>
                            <th style="padding: 0.75rem;">Email</th>
                            <th style="padding: 0.75rem;">Archivado en</th>
                            <th style="padding: 0.75rem; text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="6" style="padding: 1.5rem; text-align: center; color: #6B7075;">No hay usuarios archivados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $u): ?>
                                <tr style="border-bottom: 1px solid #D9D6CF;">
                                    <td style="padding: 0.75rem;"><?php echo $u['id']; ?></td>
                                    <td style="padding: 0.75rem;"><?php echo htmlspecialchars($u['nombre'] . ' ' . $u['apellido'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td style="padding: 0.75rem;"><?php echo htmlspecialchars($u['dni'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td style="padding: 0.75rem;"><?php echo htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td style="padding: 0.75rem;"><?php echo htmlspecialchars($u['archivado_en'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td style="padding: 0.75rem; text-align: center; white-space: nowrap;">
                                        <a href="reactivar_usuario.php?id=<?php echo $u['id']; ?>" style="color: #2F7D4F; text-decoration: none; font-weight: 500;" onclick="return confirm('¿Estás seguro de reactivar este usuario?');">Reactivar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>