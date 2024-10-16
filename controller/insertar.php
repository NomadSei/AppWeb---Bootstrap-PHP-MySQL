<?php
include('../conexion.php');
$con = conectaDB();

$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$existencia = $_POST['existencia'];

if (empty($nombre) || empty($precio) || empty($existencia)) {
    echo json_encode(['success' => 0, 'message' => 'Todos los campos son obligatorios.']);
    exit();
}

$sql = "INSERT INTO tb_productos (Nombre, Precio, Ext) VALUES (?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("sdi", $nombre, $precio, $existencia);

if ($stmt->execute()) {
    echo json_encode(['success' => 1, 'message' => 'Producto registrado exitosamente.']);
} else {
    echo json_encode(['success' => 0, 'message' => 'Error al registrar el producto.']);
}

$stmt->close();
$con->close();
?>
