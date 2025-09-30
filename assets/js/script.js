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

    // Função para exibir alert baseado em msg
    function showAlert(msg) {
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
            case "erro_update":
                alert("Erro ao atualizar veículo. Tente novamente.");
                break;
            case "sucesso":
                alert("Veículo cadastrado com sucesso!");
                break;
            case "sucesso_edit":
                alert("Veículo atualizado com sucesso!");
                break;
            case "not_found":
                alert("Veículo não encontrado.");
                break;
            default:
                // Nenhuma ação se não houver mensagem conhecida
                break;
        }
    }

    // Capturar mensagem da URL
    const params = new URLSearchParams(window.location.search);
    if (params.has("msg")) {
        showAlert(params.get("msg"));

        // Limpar msg da URL para não repetir alerta ao recarregar
        if (history.replaceState) {
            const url = new URL(window.location);
            url.searchParams.delete('msg');
            history.replaceState(null, '', url.pathname + url.search);
        }
    }
});
