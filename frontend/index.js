// URL base para el backend (ajusta si es necesario)
const BASE_URL = 'http://localhost:8888/PHP-BASICO/proyecto_a.de_internet/backend';

// Función para iniciar sesión
function loginUsuario() {
    const usuario = document.getElementById("usuario").value;
    const contrasena = document.getElementById("contrasena").value;

    // Verificar si los campos están vacíos
    if (!usuario || !contrasena) {
        alert("Por favor, ingresa tu usuario y contraseña.");
        return;
    }

    fetch(`${BASE_URL}/login.php`, {
        method: "POST",
        body: new URLSearchParams({
            'usuario': usuario,
            'contrasena': contrasena
        }),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response => response.json()) // Asegurarse de que la respuesta sea JSON
    .then(data => {
        if (data.success) {
            alert(data.message); // Muestra el mensaje de éxito
            window.location.href = "dashboard.html"; // Redirige a la página de dashboard
        } else {
            alert(data.message); // Muestra el mensaje de error
        }
    })
    .catch(error => {
        console.error("Error en el inicio de sesión:", error);
        alert("Error en el inicio de sesión. Intenta nuevamente.");
    });
}

// Función para manejar el formulario de agregar productos
document.addEventListener('DOMContentLoaded', function() {
    // Asegurarse de que el formulario sea procesado correctamente
    const form = document.getElementById('addInventoryForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Obtener los datos del formulario
        const productoNombre = document.getElementById('producto_nombre').value;
        const productoDescripcion = document.getElementById('producto_descripcion').value;
        const productoPrecio = document.getElementById('producto_precio').value;
        const accion = document.getElementById('accion').value;
        const cantidad = document.getElementById('cantidad').value;
        const adminId = document.getElementById('admin_id').value;

        // Crear el objeto del producto con las claves correctas
        const producto = {
            producto_nombre: productoNombre,  // Cambiado para que coincida con lo que espera el servidor
            producto_descripcion: productoDescripcion,  // Cambiado para que coincida con lo que espera el servidor
            producto_precio: productoPrecio,  // Cambiado para que coincida con lo que espera el servidor
            accion: accion,
            cantidad: cantidad,
            admin_id: adminId  // Asegúrate de que este valor sea correcto y válido
        };

        // Enviar los datos al servidor
        console.log("Datos enviados:", producto);
        fetch(`${BASE_URL}/addproducto.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(producto)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del servidor:', data);
            if (data.success) {
                agregarProductoTabla(data.producto);
            } else {
                alert('Error: ' + data.message); // Mostrar mensaje de error
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            alert('Hubo un error al agregar el producto.');
        });        
    });

    // Cargar los productos al iniciar la página
    cargarProductos();
});

// Función para agregar el producto a la tabla
function agregarProductoTabla(producto) {
    // Asegúrate de que el tbody con id 'products-table' exista
    const tableBody = document.getElementById('products-table');
    if (!tableBody) {
        console.error('El elemento tbody con id "products-table" no se encuentra.');
        return;
    }

    // Crear una nueva fila en la tabla
    const row = tableBody.insertRow();

    // Insertar celdas con los datos del producto
    row.insertCell(0).textContent = producto.pro_nombre;
    row.insertCell(1).textContent = producto.pro_descripcion;
    row.insertCell(2).textContent = producto.pro_precio;
    row.insertCell(3).textContent = producto.pro_cantidad;
    row.insertCell(4).innerHTML = `<button class="btn btn-danger" onclick="eliminarProducto(this)">Eliminar</button>`;
}

// Función para eliminar un producto de la tabla
function eliminarProducto(button) {
    const row = button.parentElement.parentElement;
    row.remove();
}

// Función para cargar todos los productos desde la base de datos
function cargarProductos() {
    fetch(`${BASE_URL}/getproducto.php`) // Llamamos al archivo PHP que devuelve los productos
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Si la respuesta es exitosa, mostramos los productos en la tabla
                const productos = data.productos;
                productos.forEach(producto => {
                    agregarProductoTabla(producto); // Llamamos a la función para agregar cada producto a la tabla
                });
            } else {
                alert('No se pudieron cargar los productos');
            }
        })
        .catch(error => {
            console.error('Error al obtener productos:', error);
            alert('Hubo un error al cargar los productos.');
        });
}
