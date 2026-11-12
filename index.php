<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Style - Bienvenido</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <header class="nav-inicio">
        <h1>Urban Style</h1>
        <div>
        <a href="login.php" class="btn-primario">Inicia Sesion</a>
        <a href="registro.php" class="btn-secundario">Registrate</a>
        </div>
    </header>

    <main>
        <div class="hero-section">
            <h2>Bienvenido a Urban Style</h2>
            <div class="hero-imagen">
                <img src="img/portada.avif" alt="Portada Urban Style">
            </div>
            <h3>Encontra la mejor indumentaria y accesorios urbanos en un solo lugar.</h3>
            <div class="galeria-imagenes">
                <div class="galeria-item">
                    <img src="img/zapatillas.webp" alt="zapatillas">
                </div>
                <div class="galeria-item">
                    <img src="img/buzo.webp" alt="buzo">
                </div>
                <div class="galeria-item">
                    <img src="img/gorras.webp" alt="gorras">
                </div>
            </div>

            <a href="login.php" class="btn-primario">Acceder al Sistema</a>
        </div>
    </main>

</body>
</html>