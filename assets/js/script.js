// Confirmar exclusão de veículo
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const vehicleName = this.dataset.owner;
            if (!confirm(`Deseja realmente excluir o veículo de ${vehicleName}?`)) {
                e.preventDefault();
            }
        });
    });
});