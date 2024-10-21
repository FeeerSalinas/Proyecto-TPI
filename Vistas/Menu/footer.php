<script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    let sidebarVisible = true;

    document.getElementById('sidebarToggle').addEventListener('click', () => {
        if (sidebarVisible) {
            sidebar.style.marginLeft = '-250px'; // Esconder sidebar
            content.style.marginLeft = '0'; // Ajustar contenido
        } else {
            sidebar.style.marginLeft = '0'; // Mostrar sidebar
            content.style.marginLeft = '250px'; // Ajustar contenido
        }
        sidebarVisible = !sidebarVisible;
    });
</script>
