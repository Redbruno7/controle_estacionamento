document.addEventListener("DOMContentLoaded", function() {
    // Confirmar exclusão de veículo
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const vehicleName = this.dataset.owner;
            if (!confirm(`Deseja realmente excluir o veículo de ${vehicleName}?`)) {
                e.preventDefault();
            }
        });
    });

    // Mensagens de erro
    const params = new URLSearchParams(window.location.search);
    if (params.has("msg")) {
        const msg = params.get("msg");
        switch (msg) {
            case "placa":
                alert("Já existe um veículo cadastrado com esta placa!");
                break;
            case "campos_vazios":
                alert("Preencha os campos necessários.");
                break;
            case "db_error":
                alert("Erro de banco de dados. Verifique o servidor (log).");
                break;
            case "erro_insert":
                alert("Erro ao salvar veículo. Tente novamente.");
                break;
            case "sucesso":
                break;
            default:
                break;
        }

        // Não repetir mensagem ao recarregar a página
        if (history.replaceState) {
            const url = new URL(window.location);
            url.searchParams.delete('msg');
            history.replaceState(null, '', url.pathname + url.search);
        }
    }
});
