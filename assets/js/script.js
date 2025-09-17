function confirmDelete(id, url) {
    if (confirm("Deseja realmente excluir este registro?")) {
        window.location.href = url + "?id=" + id;
    }
}