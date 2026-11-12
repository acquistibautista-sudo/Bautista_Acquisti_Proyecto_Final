<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'usuario') {
    header('Location: login.php');
    exit;
}

try {
    $sql = "SELECT nombre, apellido, dni, email, creado_en FROM usuarios WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute(['id' => $_SESSION['usuario_id']]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('Error al cargar la información del usuario.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Urban Style - Mi Panel</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="form-container-page">
        <div class="form-card" style="max-width: 600px;">
            <h2>Panel de Cliente</h2>
            <p class="form-subtitle">Bienvenido, <?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?></p>

            <div style="margin: 1.5rem 0; text-align: left; background: #F5F3EE; padding: 1.5rem; border-radius: 8px;">
                <p style="margin-bottom: 0.8rem;"><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p style="margin-bottom: 0.8rem;"><strong>Apellido:</strong> <?php echo htmlspecialchars($usuario['apellido'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p style="margin-bottom: 0.8rem;"><strong>DNI:</strong> <?php echo htmlspecialchars($usuario['dni'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p style="margin-bottom: 0.8rem;"><strong>Correo electrónico:</strong> <?php echo htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p style="margin-bottom: 0;"><strong>Fecha de registro:</strong> <?php echo htmlspecialchars($usuario['creado_en'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 1.5rem;">
                <a href="cerrar_sesion.php" class="btn-submit" style="background-color: #B3261E; text-decoration: none; text-align: center; display: inline-block; padding: 0.75rem 1.5rem;">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>