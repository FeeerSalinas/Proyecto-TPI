const styles = document.createElement('style');
styles.textContent = `
.custom-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 90%;
}

.modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.modal-button {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
}

.confirm-button {
    background-color: #4CAF50;
    color: white;
}

.cancel-button {
    background-color: #f44336;
    color: white;
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 4px;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1001;
}

.notification.success {
    background-color: #4CAF50;
}

.notification.error {
    background-color: #f44336;
}
`;
document.head.appendChild(styles);

// Función para mostrar notificaciones
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '1';
    }, 100);

    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Función para mostrar modal de confirmación
function showConfirmModal(message, onConfirm) {
    const modal = document.createElement('div');
    modal.className = 'custom-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <p>${message}</p>
            <div class="modal-buttons">
                <button class="modal-button cancel-button">Cancelar</button>
                <button class="modal-button confirm-button">Confirmar</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    modal.style.display = 'block';

    const confirmButton = modal.querySelector('.confirm-button');
    const cancelButton = modal.querySelector('.cancel-button');

    confirmButton.addEventListener('click', () => {
        onConfirm();
        document.body.removeChild(modal);
    });

    cancelButton.addEventListener('click', () => {
        document.body.removeChild(modal);
    });
}

// Función para procesar la respuesta
async function processResponse(response) {
    const data = await response.json();
    
    if (!response.ok) {
        throw new Error(data.message || 'Error en la solicitud');
    }
    
    return data;
}

// Funciones principales
function aceptarPropuesta(idPropuesta) {
    showConfirmModal('¿Estás seguro de que deseas aceptar esta propuesta?', () => {
        actualizarEstadoPropuesta(idPropuesta, 'aceptada');
    });
}

function rechazarPropuesta(idPropuesta) {
    showConfirmModal('¿Estás seguro de que deseas rechazar esta propuesta?', () => {
        actualizarEstadoPropuesta(idPropuesta, 'rechazada');
    });
}

function eliminarPropuesta(idPropuesta) {
    showConfirmModal('¿Estás seguro de que deseas eliminar esta propuesta? Esta acción no se puede deshacer.', () => {
        fetch('actualizarPropuesta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                idPropuesta: idPropuesta,
                accion: 'eliminar'
            })
        })
        .then(processResponse)
        .then(data => {
            showNotification(data.message);
            setTimeout(() => location.reload(), 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message, 'error');
        });
    });
}

function actualizarEstadoPropuesta(idPropuesta, estado) {
    fetch('actualizarPropuesta.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            idPropuesta: idPropuesta,
            estado: estado,
            accion: 'actualizar'
        })
    })
    .then(processResponse)
    .then(data => {
        showNotification(data.message);
        setTimeout(() => location.reload(), 1500);
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message, 'error');
    });
}