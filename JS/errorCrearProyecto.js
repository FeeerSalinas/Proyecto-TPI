document.getElementById('proyectoForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Mostrar indicador de carga
    Swal.fire({
        title: 'Guardando proyecto...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('procesar_proyecto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                confirmButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Limpiar el formulario
                    document.getElementById('proyectoForm').reset();
                }
            });
        } else {
            throw new Error(data.message || 'Error desconocido');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Hubo un error al procesar la solicitud',
            confirmButtonColor: '#d33'
        });
    });
});