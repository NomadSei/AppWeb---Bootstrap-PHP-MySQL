<?php
session_start();
include('../conexion.php');

$con = conectaDB();
$username = $_POST['loginUsername'];
$password = $_POST['loginPassword'];

// Modificar la consulta para obtener también el nombre completo del usuario
$sql = "SELECT NomUser, Nombre FROM tb_usuarios WHERE NomUser = ? AND Passwd = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); // Obtener los datos del usuario, incluyendo 'Nombre'
    $_SESSION['login'] = true;
    $_SESSION['nomusuario'] = $username; // Guardar el nombre de usuario
    $_SESSION['nom_completo'] = $row['Nombre']; // Guardar el nombre completo desde la tabla
    echo json_encode(['success' => 1]);
} else {
    echo json_encode(['success' => 0]);
}
?>
