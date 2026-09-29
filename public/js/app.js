document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-confirm]').forEach(form => {
        form.addEventListener('submit', event => {
            if (!window.confirm(form.dataset.confirm)) event.preventDefault();
        });
    });

    document.querySelectorAll('.status-change').forEach(select => {
        select.addEventListener('change', async () => {
            const id = select.dataset.id;
            const status = select.value;
            select.disabled = true;

            try {
                const response = await fetch(`/api/applications/${id}/status`, {
                    method: 'PATCH',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({status})
                });
                const result = await response.json();
                if (!result.success) throw new Error(result.message || 'Update failed');
                window.location.reload();
            } catch (error) {
                alert(error.message);
                window.location.reload();
            }
        });
    });

    document.querySelectorAll('.flash').forEach(el => {
        setTimeout(() => el.remove(), 4500);
    });
});
