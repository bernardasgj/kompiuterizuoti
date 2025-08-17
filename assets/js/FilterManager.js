/**
 * FilterManager handles filtering posts via the #filterForm.
 * Sends AJAX requests on form submission and updates the posts table dynamically.
 */
class FilterManager {
    constructor() {
        this.initEvents();
    }

    /**
     * Initialize event listeners for the filter form
     */
    initEvents() {
        $('#filterForm').on('submit', (e) => this.handleFilterSubmit(e));
    }

    /**
     * Handle filter form submission
     * @param {Event} e
     */
    handleFilterSubmit(e) {
        e.preventDefault();

        $('#tableLoadingOverlay').show();

        const params = $('#filterForm').serialize();
        const newUrl = `/posts?${params}`;

        // Save filters in URL without reload
        window.history.pushState({}, '', newUrl);

        $.ajax({
            url: newUrl,
            type: 'GET',
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .done((data) => {
            if (data.table) {
                $('.posts-table').html(data.table);
            }

            $('.posts-pagination').html(data.pagination);

            ToastManager.show('Posts filtered successfully', 'success');
        })
        .fail((xhr) => {
            const message = xhr.responseJSON?.message || 'Error filtering posts';
            ToastManager.show(message, 'danger');
        })
        .always(() => $('#tableLoadingOverlay').hide());
    }
}

new FilterManager();
