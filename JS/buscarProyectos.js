function filtrarProyectos() {
    const categoriaId = document.getElementById('categoriaSelector').value;
    if (categoriaId) {
        document.getElementById('categoriaSelector').value = categoriaId;
        window.location.href = `buscarProyectos.php?idCategoria=${categoriaId}`;
    }else{
        window.location.href = `buscarProyectos.php`;
    }
}