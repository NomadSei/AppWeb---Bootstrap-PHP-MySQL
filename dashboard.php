<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}
include('conexion.php');
$con = conectaDB();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Pruebas UNACH</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="estilos.css">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Usuario: <?php echo $_SESSION['nom_completo']; ?></span>
        <a href="cerrar.php" class="btn btn-warning">Cerrar Sesión</a>
    </div>
</nav>

<div class="container mt-5">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Listado de Productos</h3>
    <button type="button" class="btn btn-nuevo-producto" data-bs-toggle="modal" data-bs-target="#newProductModal">
        Nuevo Producto
    </button>
</div>

    <!-- Filtro por precio -->
    <div class="mb-3">
        <label for="priceFilter" class="form-label">Buscar producto por precio mayor a:</label>
        <input type="number" class="form-control d-inline-block w-auto" id="priceFilter" placeholder="Ej. 100">
        <button class="btn btn-primary" id="filterButton">Buscar</button>
    </div>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="productsBody">
            <!-- Productos cargados dinámicamente -->
        </tbody>
    </table>
</div>

<!-- Modal para Registrar Nuevo Producto -->
<div class="modal fade" id="newProductModal" tabindex="-1" aria-labelledby="newProductLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newProductLabel">Registrar Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newProductForm">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="productPrice" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="productStock" class="form-label">Existencia</label>
                        <input type="number" class="form-control" id="productStock" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger custom-close" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" id="saveProduct">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Producto -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" id="editProductId">
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="editProductName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductPrice" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="editProductPrice" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductStock" class="form-label">Existencia</label>
                        <input type="number" class="form-control" id="editProductStock" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger custom-close" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="updateProduct">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Cargar productos al inicio
    function loadProducts(price = '') {
        $.get('controller/fetch_products.php', { price: price }, function (data) {
            $('#productsBody').html(data);
        }).fail(function () {
            alert('Error al cargar los productos.');
        });
    }

    loadProducts(); // Cargar todos los productos al inicio

    // Filtrar productos por precio
    $('#filterButton').click(function () {
        const price = $('#priceFilter').val();
        loadProducts(price);
    });

    // Guardar nuevo producto
    $('#saveProduct').click(function () {
        const name = $('#productName').val();
        const price = $('#productPrice').val();
        const stock = $('#productStock').val();

        if (!name || !price || !stock) {
            alert('Todos los campos son obligatorios.');
            return;
        }

        $.post('controller/insertar.php', {
            nombre: name,
            precio: price,
            existencia: stock
        }, function (data) {
            const response = JSON.parse(data);
            if (response.success) {
                alert(response.message);
                $('#newProductModal').modal('hide');
                loadProducts();
            }
        }).fail(function () {
            alert('Error al guardar el producto.');
        });
    });

    // Eliminar producto
    $(document).on('click', '.delete-product', function () {
        const id = $(this).data('id');
        if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
            $.get('controller/eliminar.php', { idp: id }, function (data) {
                const response = JSON.parse(data);
                if (response.success) {
                    alert(response.message);
                    loadProducts();
                }
            }).fail(function () {
                alert('Error en la solicitud de eliminación.');
            });
        }
    });

    // Abrir modal para editar producto
    $(document).on('click', '.edit-product', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const price = $(this).data('price');
        const stock = $(this).data('stock');
        $('#editProductId').val(id);
        $('#editProductName').val(name);
        $('#editProductPrice').val(price);
        $('#editProductStock').val(stock);
        $('#editProductModal').modal('show');
    });

    // Actualizar producto
    $('#updateProduct').click(function () {
        const id = $('#editProductId').val();
        const name = $('#editProductName').val();
        const price = $('#editProductPrice').val();
        const stock = $('#editProductStock').val();

        $.post('controller/actualizar.php', {
            id: id,
            nombre: name,
            precio: price,
            existencia: stock
        }, function (data) {
            const response = JSON.parse(data);
            if (response.success) {
                alert(response.message);
                $('#editProductModal').modal('hide');
                loadProducts();
            }
        }).fail(function () {
            alert('Error en la solicitud de actualización.');
        });
    });
});
</script>

<script>
    window.addEventListener("beforeunload", function (event) {
        navigator.sendBeacon("cerrar.php"); // Llama a cerrar.php para cerrar la sesión
    });
</script>

</body>
</html>
