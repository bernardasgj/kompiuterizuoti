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
    
        const urlParams = new URLSearchParams($('#filterForm').serialize());
    
        // preserve current page from URL if present
        const currentPage = new URLSearchParams(window.location.search).get('page');
        if (currentPage) {
            urlParams.set('page', currentPage);
        } else {
            urlParams.set('page', 1);
        }
    
        const newUrl = `/posts?${urlParams.toString()}`;
    
        window.history.pushState({}, '', newUrl);
    
        $.ajax({
            url: newUrl,
            type: 'GET',
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .done((data) => {
            if (data.table) $('.posts-table').html(data.table);
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
