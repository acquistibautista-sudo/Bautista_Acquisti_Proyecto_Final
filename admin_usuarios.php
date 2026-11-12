<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$busqueda = trim($_GET['busqueda'] ?? '');

try {
    if ($busqueda !== '') {
        $sql = "SELECT id, nombre, apellido, dni, email, rol, creado_en 
                FROM usuarios 
                WHERE archivado_en IS NULL 
                AND (nombre LIKE :busq OR apellido LIKE :busq OR dni LIKE :busq OR email LIKE :busq)
                ORDER BY id DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['busq' => "%$busqueda%"]);
    } else {
        $sql = "SELECT id, nombre, apellido, dni, email, rol, creado_en 
                FROM usuarios 
                WHERE archivado_en IS NULL 
                ORDER BY id DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
    }

    $usuarios = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $error = "Error al obtener la lista de usuarios.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Urban Style</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="contenedor-admin">
        <div class="encabezado-admin">
            <h2>Panel de Administración - Usuarios Activos</h2>
            <div>
                <a href="usuarios_archivados.php" class="btn-secundario">Ver Archivados</a>
                <a href="cerrar_sesion.php" class="btn-peligro">Cerrar Sesión</a>
            </div>
        </div>

        <form method="GET" action="admin_usuarios.php" class="form-busqueda">
            <input type="text" name="busqueda" placeholder="Buscar por nombre, apellido, DNI o email..." value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit" class="btn-primario">Buscar</button>
        </form>

        <?php if (!empty($usuarios)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre y Apellido</th>
                        <th>DNI</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['id']); ?></td>
                            <td><?php echo htmlspecialchars($u['nombre'] . ' ' . $u['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($u['dni']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo htmlspecialchars($u['rol']); ?></td>
                            <td><?php echo htmlspecialchars($u['creado_en']); ?></td>
                            <td>
                                <a href="editar_usuario.php?id=<?php echo $u['id']; ?>" class="btn-tabla btn-editar">Editar</a>
                                <a href="archivar_usuario.php?id=<?php echo $u['id']; ?>" class="btn-tabla btn-archivar" onclick="return confirm('¿Seguro que deseas archivar este usuario?');">Archivar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="sin-resultados">No se encontraron usuarios activos.</p>
        <?php endif; ?>
    </div>
</body>
</html>