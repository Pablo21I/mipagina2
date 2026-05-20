// app.js - Lógica SPA para el Módulo B

document.addEventListener('DOMContentLoaded', () => {
    // 1. Atrapamos elementos del DOM
    const formProducto = document.getElementById('form-producto');
    const tbody = document.getElementById('cuerpo-tabla');
    const btnGuardar = document.getElementById('btn-guardar');
    const btnActualizar = document.getElementById('btn-actualizar');
    const btnCancelar = document.getElementById('btn-cancelar');
    const btnRefrescar = document.getElementById('btn-refrescar');
    const divMensaje = document.getElementById('mensaje-sistema');
    const formTitle = document.getElementById('form-title');
    
    const inputId = document.getElementById('prod-id');
    
    // URL de nuestra API
    const API_URL = 'api.php';
    
    // Función para mostrar mensajes
    const mostrarMensaje = (texto, tipo) => {
        divMensaje.textContent = texto;
        divMensaje.className = `msg-${tipo}`;
        divMensaje.style.display = 'block';
        setTimeout(() => {
            divMensaje.style.display = 'none';
        }, 4000);
    };

    // 2. FUNCIÓN LEER (READ) - Cargar productos
    const cargarProductos = () => {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</td></tr>';
        
        fetch(API_URL)
            .then(respuesta => respuesta.json())
            .then(datos => {
                tbody.innerHTML = '';
                
                if (datos.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #6b7280;">No hay productos registrados.</td></tr>';
                    return;
                }
                
                datos.forEach(producto => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td><strong>${producto.codigo}</strong></td>
                        <td>${producto.descripcion}</td>
                        <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                        <td>${producto.cantidad}</td>
                        <td>
                            <button class="btn-accion btn-edit-row" 
                                data-id="${producto.id}"
                                data-codigo="${producto.codigo}"
                                data-desc="${producto.descripcion}"
                                data-precio="${producto.precio}"
                                data-cantidad="${producto.cantidad}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-accion btn-delete-row" data-id="${producto.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                });
            })
            .catch(error => {
                console.error('Error al cargar:', error);
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: red;">Error al conectar con el servidor.</td></tr>';
            });
    };

    // 3. FUNCIÓN CREAR Y ACTUALIZAR (CREATE/UPDATE)
    formProducto.addEventListener('submit', (e) => {
        e.preventDefault(); // Evitar recarga de página (FRENO DE MANO)
        
        // Empaquetar datos del formulario
        const datosFormulario = new FormData(formProducto);
        const isUpdate = inputId.value !== "";
        
        mostrarMensaje(isUpdate ? "Actualizando..." : "Guardando...", "info");
        
        fetch(API_URL, {
            method: 'POST',
            body: datosFormulario
        })
        .then(respuesta => respuesta.json())
        .then(data => {
            if (data.status === 'success') {
                mostrarMensaje(data.message, "success");
                formProducto.reset();
                cancelarEdicion();
                cargarProductos(); // Recargar tabla
            } else {
                mostrarMensaje(data.message, "error");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje("Error de comunicación con el servidor.", "error");
        });
    });

    // 4. DELEGACIÓN DE EVENTOS PARA EDITAR Y ELIMINAR
    tbody.addEventListener('click', (e) => {
        // Encontrar el botón (puede que hayan clickeado el ícono dentro del botón)
        const btnEditar = e.target.closest('.btn-edit-row');
        const btnEliminar = e.target.closest('.btn-delete-row');
        
        // Acción: PREPARAR EDICIÓN
        if (btnEditar) {
            inputId.value = btnEditar.dataset.id;
            document.getElementById('prod-codigo').value = btnEditar.dataset.codigo;
            document.getElementById('prod-descripcion').value = btnEditar.dataset.desc;
            document.getElementById('prod-precio').value = btnEditar.dataset.precio;
            document.getElementById('prod-cantidad').value = btnEditar.dataset.cantidad;
            
            // Cambiar vista del formulario
            formTitle.textContent = "Editar Producto";
            btnGuardar.style.display = 'none';
            btnActualizar.style.display = 'block';
            btnCancelar.style.display = 'block';
            
            // Hacer scroll suave hacia el formulario
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Acción: ELIMINAR (DELETE)
        if (btnEliminar) {
            const idEliminar = btnEliminar.dataset.id;
            
            if (confirm('¿Estás seguro de que deseas eliminar este producto de forma permanente?')) {
                fetch(API_URL, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: idEliminar })
                })
                .then(respuesta => respuesta.json())
                .then(data => {
                    if (data.status === 'success') {
                        mostrarMensaje(data.message, "success");
                        cargarProductos(); // Recargar la tabla
                    } else {
                        mostrarMensaje(data.message, "error");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarMensaje("Error al eliminar el producto.", "error");
                });
            }
        }
    });

    // Función para restaurar el formulario a modo "Crear"
    const cancelarEdicion = () => {
        inputId.value = "";
        formProducto.reset();
        formTitle.textContent = "Nuevo Producto";
        btnGuardar.style.display = 'block';
        btnActualizar.style.display = 'none';
        btnCancelar.style.display = 'none';
    };

    // Eventos extra
    btnCancelar.addEventListener('click', cancelarEdicion);
    btnActualizar.addEventListener('click', () => {
        // Disparar evento submit manualmente
        formProducto.dispatchEvent(new Event('submit'));
    });
    btnRefrescar.addEventListener('click', cargarProductos);

    // Cargar datos por primera vez al abrir la página
    cargarProductos();
});
