// Mensagem de erro - Entradas e Vehicles
document.addEventListener('DOMContentLoaded', function() {

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

    // Captura do form de registro
    const form = document.querySelector("form");
    if (!form) return;

    form.addEventListener("submit", async function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        let endpoint = "add.php"; // padrão relativo
        const path = window.location.pathname;

        if (path.includes("/vehicles/")) {
            endpoint = "add.php";
        } else if (path.includes("/entries/")) {
            endpoint = "add.php";
        }

        try {
            const response = await fetch(endpoint, {
                method: "POST",
                body: formData
            });

            const text = await response.text();

            if (text.includes("Este veículo já está estacionado")) {
                alert("Este veículo já está estacionado e não pode registrar nova entrada.");
            } else if (text.includes("Veículo inválido")) {
                alert("Veículo inválido.");
            } else if (text.includes("Estacionamento lotado")) {
                alert("Estacionamento lotado!");
            } else if (text.includes("Placa já cadastrada")) {
                alert("Já existe um veículo cadastrado com esta placa!");
            } else if (text.includes("success")) {
                window.location.href = "list.php";
            } else {
                alert(text);
            }

        } catch (err) {
            console.error("Erro ao enviar formulário:", err);
            alert("Erro ao processar a requisição. Tente novamente.");
        }
    });
});