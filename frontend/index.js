// URL base para el backend (ajusta si es necesario)
const BASE_URL = 'http://localhost:8888/proyecto_a.de_internet/backend';

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
document.addEventListener('DOMContentLoaded', function () {
    // Asegurarse de que el formulario sea procesado correctamente
    const form = document.getElementById('addInventoryForm');
    form.addEventListener('submit', function (e) {
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
    console.log(producto);  // Verifica que los datos del producto sean correctos

    // Crear una nueva fila para el producto
    const row = document.createElement('tr');
    row.setAttribute('data-id', producto.pro_id); // Establecer el ID del producto en el atributo 'data-id'

    // Crear las celdas con los datos del producto
    row.innerHTML = `
        <td>${producto.pro_nombre}</td>
        <td>${producto.pro_descripcion}</td>
        <td>${producto.pro_precio}</td>
        <td>
            <button class="btn btn-danger" onclick="eliminarProducto(this)">Eliminar</button>
        </td>
    `;

    // Agregar la fila a la tabla
    const tableBody = document.getElementById('products-table');
    if (tableBody) {
        tableBody.appendChild(row);
    } else {
        console.error('No se encontró el elemento de la tabla.');
    }
}


// Función para eliminar un producto de la tabla
function eliminarProducto(button) {
    const row = button.parentElement.parentElement; // Obtiene la fila de la tabla
    const productoId = row.getAttribute('data-id'); // Obtiene el ID del producto (debe estar en el atributo data-id de la fila)

    // Verifica que el productoId existe
    if (!productoId) {
        alert('Error: no se encontró el ID del producto');
        return;
    }

    // Confirmación antes de eliminar
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        // Hacer una solicitud al servidor para eliminar el producto
        fetch('../backend/deleteProducto.php', {  // La URL del archivo PHP para eliminar el producto
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                producto_id: productoId  // Enviar el ID del producto
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Si la eliminación fue exitosa, elimina la fila de la tabla
                    row.remove();
                    alert('Producto eliminado exitosamente');
                } else {
                    alert('Error al eliminar el producto: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un problema con la solicitud');
            });
    }
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
