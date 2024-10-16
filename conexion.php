<?php
function conectaDB() {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'miempresa';

    $link = new mysqli($host, $user, $pass, $db);
    if ($link->connect_error) {
        die("Error de conexión: " . $link->connect_error);
    }
    return $link;
}
?>
