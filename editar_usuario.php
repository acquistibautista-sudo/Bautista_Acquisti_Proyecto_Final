<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: admin_usuarios.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $rol = $_POST['rol'] ?? 'usuario';

    if (empty($nombre) || empty($apellido) || empty($dni) || empty($email) || empty($rol)) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del correo electrónico no es válido.";
    }

    if (empty($errores)) {
        try {
            $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, dni = :dni, email = :email, rol = :rol WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'dni' => $dni,
                'email' => $email,
                'rol' => $rol,
                'id' => $id
            ]);

            header('Location: admin_usuarios.php');
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errores[] = "Error al actualizar los datos del usuario.";
        }
    }
} else {
    try {
        $sql = "SELECT nombre, apellido, dni, email, rol FROM usuarios WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['id' => $id]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            header('Location: admin_usuarios.php');
            exit;
        }

        $nombre = $usuario['nombre'];
        $apellido = $usuario['apellido'];
        $dni = $usuario['dni'];
        $email = $usuario['email'];
        $rol = $usuario['rol'];
    } catch (PDOException $e) {
        error_log($e->getMessage());
        exit('Error al cargar el usuario.');
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Urban Style - Editar Usuario</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="form-container-page">
        <div class="form-card">
            <h2>Editar Usuario</h2>
            <p class="form-subtitle">Modificá los datos del sistema</p>

            <?php if (!empty($errores)): ?>
                <div style="color: #B3261E; margin-bottom: 1rem; font-size: 0.9rem;">
                    <?php foreach ($errores as $error): ?>
                        <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="editar_usuario.php?id=<?php echo $id; ?>" method="POST" class="form-flex">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" maxlength="80" value="<?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" maxlength="80" value="<?php echo htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="dni">DNI</label>
                    <input type="text" id="dni" name="dni" maxlength="8" value="<?php echo htmlspecialchars($dni, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" maxlength="254" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select id="rol" name="rol" required style="width: 100%; padding: 0.75rem; border: 1px solid #D9D6CF; border-radius: 4px;">
                        <option value="usuario" <?php echo $rol === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                        <option value="admin" <?php echo $rol === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Guardar Cambios</button>
            </form>
            
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                <a href="admin_usuarios.php" style="color: #6B7075; text-decoration: none;">Volver al panel</a>
            </p>
        </div>
    </div>
</body>
</html>