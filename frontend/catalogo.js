document.addEventListener("DOMContentLoaded", function () {
    const BASE_URL = 'http://localhost:8888/proyecto_a.de_internet/backend';
    let productos = []; // Para almacenar los productos cargados desde el backend
    const usuarioId = 1; // Suponiendo que el usuario está autenticado y su ID es 1 (esto debería cambiar dependiendo de tu sistema de autenticación)

    // Obtener productos desde el backend
    fetch(`${BASE_URL}/getproducto.php`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                productos = data.productos; // Guardamos los productos en la variable
                renderProductos(productos);
            } else {
                alert('Error al cargar los productos: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));

    // Renderizar productos en la tabla
    const renderProductos = (productos) => {
        const tableBody = document.getElementById("products-table");
        tableBody.innerHTML = ""; // Limpia el contenido previo

        productos.forEach((producto) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${producto.pro_nombre}</td>
                <td>${producto.pro_descripcion}</td>
                <td>$${parseFloat(producto.pro_precio).toFixed(2)}</td>
                <td>${producto.inv_cantidad}</td>
                <td>
                    <button class="btn btn-success btn-sm me-2" onclick="agregarAlCarrito(${producto.pro_id}, ${producto.inv_cantidad})">
                        Agregar
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    };

    // Agregar al carrito
    window.agregarAlCarrito = function (productoId, cantidadDisponible) {
        const cantidad = prompt("¿Cuántos productos deseas agregar?", 1);

        if (cantidad > cantidadDisponible) {
            alert("No hay suficiente stock.");
            return;
        }

        // Enviar los datos al backend para agregar el producto al carrito
        fetch(`${BASE_URL}/addcarrito.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                usuarioId: usuarioId,
                productoId: productoId,
                cantidad: parseInt(cantidad)
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Una vez que el producto se ha agregado correctamente, actualizamos el resumen del carrito
                    cargarCarrito();
                } else {
                    alert("Error al agregar el producto: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
    };

    // Actualizar el resumen del carrito
    const cargarCarrito = () => {
        console.log('Cargando carrito...');
        // Realizar la solicitud para obtener el carrito
        console.log(`Solicitando carrito para usuarioId: ${usuarioId}`);
    
        fetch(`${BASE_URL}/getcarrito.php?usuarioId=${usuarioId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta recibida del servidor:', data);
                if (data.success) {
                    const carrito = data.productos;
                    const carritoBody = document.getElementById("carritoBody");
                    const carritoTotal = document.getElementById("carritoTotal");

                    let total = 0;
                    carritoBody.innerHTML = "";

                    carrito.forEach((item, index) => {
                        console.log('Producto en carrito:', item);

                        const totalProducto = item.det_cantidad * item.pro_precio;
                        total += totalProducto;

                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${item.pro_nombre}</td>
                            <td>
                                <input type="number" 
                                    class="form-control form-control-sm" 
                                    value="${item.det_cantidad}" 
                                    min="1" 
                                    id="cantidad_${item.det_id}" 
                                    onchange="actualizarCantidad(${item.det_id})">
                            </td>
                            <td>$${item.pro_precio}</td>
                            <td>$${totalProducto.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${item.det_id})">Eliminar</button>
                            </td>
                        `;
                        carritoBody.appendChild(row);
                    });

                    carritoTotal.textContent = `$${total.toFixed(2)}`;
                } else {
                    alert("No se pudo cargar el carrito.");
                }
            })
            .catch(error => {
                console.error('Error al cargar el carrito:', error);
                alert("Hubo un problema al cargar el carrito.");
            });
    };

    // Cargar el carrito al inicio
    cargarCarrito();
});