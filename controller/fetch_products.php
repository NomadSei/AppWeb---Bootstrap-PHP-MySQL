<?php
include('../conexion.php');
$con = conectaDB();

$price = isset($_GET['price']) ? $_GET['price'] : '';

if ($price !== '') {
    $sql = "SELECT idPro, Nombre, Precio, Ext FROM tb_productos WHERE Precio > ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("d", $price);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT idPro, Nombre, Precio, Ext FROM tb_productos";
    $result = $con->query($sql);
}

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['Nombre']}</td>
            <td>{$row['Precio']}</td>
            <td>{$row['Ext']}</td>
            <td>
                <button class='edit-product' 
                    data-id='{$row['idPro']}' data-name='{$row['Nombre']}' 
                    data-price='{$row['Precio']}' data-stock='{$row['Ext']}' 
                    style='background-color: transparent; border: none; padding: 0; width: 40px; height: 40px; display: inline-flex; justify-content: center; align-items: center; margin-right: 10px; cursor: pointer;'>
                    <img src='iconos/editar.png' alt='Editar' 
                        style='width: 30px; height: 30px; border-radius: 100%; object-fit: cover; display: block;'>
                </button>
                <button class='delete-product' data-id='{$row['idPro']}'
                    style='background-color: transparent; border: none; padding: 0; width: 40px; height: 40px; display: inline-flex; justify-content: center; align-items: center; cursor: pointer;'>
                    <img src='iconos/eliminar.png' alt='Eliminar' 
                        style='width: 30px; height: 30px; border-radius: 100%; object-fit: cover; display: block;'>
                </button>
            </td>
          </tr>";
}
?>
