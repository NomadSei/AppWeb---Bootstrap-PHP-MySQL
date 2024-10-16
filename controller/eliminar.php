<?php
include('../conexion.php');
$con = conectaDB();

// Verificar si el parámetro idp está presente
if (isset($_GET['idp'])) {
    $id = $_GET['idp'];

    $sql = "DELETE FROM tb_productos WHERE idPro = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 1, 'message' => 'Producto eliminado exitosamente.']);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Error al eliminar el producto.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => 0, 'message' => 'ID de producto no proporcionado.']);
}

$con->close();
?>
