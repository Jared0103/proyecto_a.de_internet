document.addEventListener('DOMContentLoaded', function() {
    // User Login
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault()
        
        const username = document.getElementById('username').value
        const password = document.getElementById('password').value

        fetch('http://localhost/tienda-php/src/routes/usuarioRoutes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'inventario.html'
            } else {
                alert('Credenciales incorrectas, inténtalo de nuevo.')
            }
        })
        .catch(error => console.error('Error:', error))
    })

    // User Registration
    document.getElementById('registroForm').addEventListener('submit', function(event) {
        event.preventDefault()
        
        const nombreUsuario = document.getElementById('nombreUsuario').value
        const email = document.getElementById('email').value
        const password = document.getElementById('password').value
        const confirmPassword = document.getElementById('confirmPassword').value

        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden')
            return
        }

        fetch('http://localhost/tienda-php/src/routes/usuarioRoutes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nombreUsuario, email, password })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.mensaje)
            if (data.success) {
                window.location.href = 'index.html'
            }
        })
        .catch(error => console.error('Error:', error))
    })

    // Add Product
    document.getElementById('addProductForm').addEventListener('submit', function(event) {
        event.preventDefault()
        
        const nombre = document.getElementById('productName').value
        const descripcion = document.getElementById('productDescription').value
        const precio = document.getElementById('productPrice').value

        fetch('http://localhost/tienda-php/src/routes/productoRoutes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nombre, descripcion, precio })
        })
        .then(response => response.json())
        .then(data => {
            alert('Producto agregado exitosamente')
            var addProductModal = new bootstrap.Modal(document.getElementById('addProductModal'))
            addProductModal.hide()
            fetchProducts() // Refresh the product list
        })
        .catch(error => console.error('Error:', error))
    })

    // Fetch and display products
    function fetchProducts() {
        fetch('http://localhost/tienda-php/src/routes/productoRoutes.php')
        .then(response => response.json())
        .then(data => {
            const productsTable = document.getElementById('products-table')
            productsTable.innerHTML = ''
            data.forEach(product => {
                const row = document.createElement('tr')
                row.innerHTML = `
                    <td>${product.pro_nombre}</td>
                    <td>${product.pro_descripcion}</td>
                    <td>$${product.pro_precio.toFixed(2)}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.pro_id})">Eliminar</button></td>
                `
                productsTable.appendChild(row)
            })
        })
        .catch(error => console.error('Error:', error))
    }

    // Delete Product
    window.deleteProduct = function(id) {
        fetch(`http://localhost/tienda-php/src/routes/productoRoutes.php/${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            alert('Producto eliminado exitosamente')
            fetchProducts()
        })
        .catch(error => console.error('Error:', error))
    }

    // Initial fetch of products
    fetchProducts()
})