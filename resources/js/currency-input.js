document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-currency]').forEach(input => {
        const hidden = document.getElementById(input.dataset.currency);

        // Format initial value if exists
        if (hidden && hidden.value) {
            const centavos = parseInt(hidden.value) || 0;
            if (centavos > 0) {
                input.value = (centavos / 100).toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                });
            }
        }

        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            let centavos = parseInt(value) || 0;
            if (hidden) {
                hidden.value = centavos;
            }
            e.target.value = (centavos / 100).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        });
    });
});
