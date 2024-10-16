<?php
include('../conexion.php');
$con = conectaDB();

// Verificar si los datos están presentes
if (isset($_POST['id'], $_POST['nombre'], $_POST['precio'], $_POST['existencia'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $existencia = $_POST['existencia'];

    // Preparar y ejecutar la consulta
    $sql = "UPDATE tb_productos SET Nombre = ?, Precio = ?, Ext = ? WHERE idPro = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sdii", $nombre, $precio, $existencia, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 1, 'message' => 'Producto actualizado exitosamente.']);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Error al actualizar el producto.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => 0, 'message' => 'Datos incompletos.']);
}

$con->close();
?>
