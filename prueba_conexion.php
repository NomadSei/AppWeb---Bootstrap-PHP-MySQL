
<?php
include('conexion.php');

$con = conectaDB();
if ($con) {
    echo "Conexión exitosa a la base de datos.";
} else {
    echo "Error al conectar a la base de datos.";
}
?>
