<?php
require_once 'conexion.php';

$errores = [];
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nombre) || empty($apellido) || empty($dni) || empty($email) || empty($password)) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del correo electronico no es valido.";
    }

    if (strlen($dni) > 8) {
        $errores[] = "El DNI no puede superar los 8 caracteres.";
    }

    if (empty($errores)) {
    try {
        $sqlCheck = "SELECT id FROM usuarios WHERE dni = :dni OR email = :email";
        $stmtCheck = $conexion->prepare($sqlCheck);
        $stmtCheck->execute(['dni' => $dni, 'email' => $email]);

    if ($stmtCheck->rowCount() > 0) {
        $errores[] = "El DNI o el correo electronico ya se encuentran registrados.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
         $sqlInsert = "INSERT INTO usuarios (nombre, apellido, dni, email, password_hash, rol) 
                    VALUES (:nombre, :apellido, :dni, :email, :password_hash, 'usuario')";
        $stmtInsert = $conexion->prepare($sqlInsert);
        $stmtInsert->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $dni,
            'email' => $email,
            'password_hash' => $passwordHash
        ]);
        $exito = "¡Cuenta creada con exito! Ya podes iniciar sesion.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $errores[] = "Ocurrio un error en la base de datos. Intente mas tarde.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Urban Style - Registro de Clientes</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="form-container-page">
        <div class="form-card">
            <h2>Crear Cuenta</h2>
            <p class="form-subtitle">Completa tus datos para registrarte en Urban Style</p>

            <?php if (!empty($errores)): ?>
                <div class="alert-error" style="color: #B3261E; margin-bottom: 1rem; font-size: 0.9rem;">
                <?php foreach ($errores as $error): ?>
                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($exito)): ?>
            <div class="alert-success" style="color: #2F7D4F; margin-bottom: 1rem; font-size: 0.9rem;">
                <p><?php echo htmlspecialchars($exito, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <?php endif; ?>

            <form action="registro.php" method="POST" class="form-flex">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" maxlength="80" required>
            </div>

            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" maxlength="80" required>
            </div>

            <div class="form-group">
                <label for="dni">DNI</label>
                <input type="text" id="dni" name="dni" maxlength="8" required>
            </div>

            <div class="form-group">
                <label for="email">Correo electronico</label>
                <input type="email" id="email" name="email" maxlength="254" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-submit">Registrarse</button>
            </form>
            
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                ¿Ya tenes cuenta? <a href="login.php" style="color: #D9480F; text-decoration: none;">Inicia sesion</a>
            </p>
        </div>
    </div>
</body>
</html>