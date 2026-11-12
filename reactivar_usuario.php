<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if ($id) {
    try {
        $sql = "UPDATE usuarios SET archivado_en = NULL WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

header('Location: usuarios_archivados.php');
exit;