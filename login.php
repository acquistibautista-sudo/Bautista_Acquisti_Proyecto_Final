<?php
require_once 'conexion.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email AND archivado_en IS NULL";
            $stmt = $conexion->prepare($sql);
            $stmt->execute(['email' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol'] = $usuario['rol'];

                if ($usuario['rol'] === 'admin') {
                    header('Location: admin_usuarios.php');
                } else {
                    header('Location: panel.php');
                }
                exit;
            } else {
                $error = "Correo o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = "Ocurrió un error en el sistema. Intente más tarde.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Urban Style - Iniciar Sesión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="form-container-page">
        <div class="form-card">
            <h2>Iniciar Sesion</h2>
            <p class="form-subtitle">Ingresá a tu cuenta de Urban Style</p>

            <?php if (!empty($error)): ?>
                <div class="alert-error" style="color: #B3261E; margin-bottom: 1rem; font-size: 0.9rem;">
                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="form-flex">
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" maxlength="254" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-submit">Ingresar</button>
            </form>

            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                ¿No tenés cuenta? <a href="registro.php" style="color: #D9480F; text-decoration: none;">Registrate</a>
            </p>
        </div>
    </div>
</body>
</html>