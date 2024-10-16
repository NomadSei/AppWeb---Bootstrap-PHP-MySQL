<?php
session_start();
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesion - Sistema de Pruebas UNACH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Acceso de Usuarios</h3>
                </div>
                <div class="card-body">
                    <form id="loginForm">
                        <div class="form-group mb-3">
                            <label for="loginUsername">Usuario</label>
                            <input type="text" class="form-control" id="loginUsername" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="loginPassword">Contraseña</label>
                            <input type="password" class="form-control" id="loginPassword" required>
                        </div>
                        <button type="button" id="loginButton" class="btn btn-primary w-100">Ingresar</button>
                    </form>
                    <div id="loginMessage" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#loginButton').on('click', function () {
        const username = $('#loginUsername').val();
        const password = $('#loginPassword').val();

        if (!username || !password) {
            $('#loginMessage').html('<div class="alert alert-danger">Todos los campos son obligatorios.</div>');
            return;
        }

        $.post('controller/validar.php', {
            loginUsername: username,
            loginPassword: password
        }, function (data) {
            const response = JSON.parse(data);
            if (response.success) {
                window.location = 'dashboard.php';
            } else {
                $('#loginMessage').html('<div class="alert alert-danger">Usuario o contraseña incorrectos.</div>');
            }
        });
    });
});
</script>
</body>
</html>
