/**
 * PaginationManager handles page navigation and per-page selection
 * for a posts table. It automatically fetches updated table content
 * via AJAX and updates pagination links.
 */
class PaginationManager {
    constructor() {
        this.initEvents();
    }

    /**
     * Initializes click event listeners for pagination links and per-page buttons.
     */
    initEvents() {
        // Handle click on page number links
        $(document).on('click', '.page-link', (e) => {
            e.preventDefault();
            const page = $(e.currentTarget).data('page');
            if (!page) return;
            this.loadPage(page);
        });

        // Handle click on per-page selection buttons
        $(document).on('click', '[data-per-page]', (e) => {
            e.preventDefault();
            const perPage = $(e.currentTarget).data('per-page');
            if (!perPage) return;
            this.changePerPage(perPage);
        });
    }

    /**
     * Load a specific table's page while keeping other filters intact.
     * @param {number} page - The page number to load
     */
    loadPage(page) {
        const urlParams = new URLSearchParams($('#filterForm').serialize());
        console.log('HERE',urlParams)

        urlParams.set('page', page);
        console.log('HERE',page)

        this.fetchPosts(urlParams);
        console.log('HERE')
    }

    /**
     * Change the number of posts displayed per page.
     * Resets page to 1.
     * @param {number} perPage - Number of items per page
     */
    changePerPage(perPage) {
        const urlParams = new URLSearchParams($('#filterForm').serialize());
        urlParams.set('per_page', perPage);
        urlParams.set('page', 1);
        this.fetchPosts(urlParams);
    }

    /**
     * Fetch posts from the server with and set given URL parameters to preserve filter between reloads.
     * Updates table content, pagination, and browser history.
     * @param {URLSearchParams} urlParams
     */
    fetchPosts(urlParams) {
        $('#tableLoadingOverlay').show();

        $.ajax({
            url: `/posts?${urlParams.toString()}`,
            type: 'GET',
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .done((data) => {
            if (data.table) {
                $('.posts-table').html(data.table);
            }

            if (data.pagination) {
                $('.posts-pagination').html(data.pagination);
            }

            window.history.pushState({}, '', '?' + urlParams.toString());
        })
        .fail((xhr) => {
            const message = xhr.responseJSON?.message || 'Error loading posts';
            ToastManager.show(message, 'danger');
        })
        .always(() => {
            $('#tableLoadingOverlay').hide();
        });
    }
}

new PaginationManager();
