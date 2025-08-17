class CrudManager {
    constructor() {
        this.initEvents();
        this.initModal();
    }
  
    /**
     * Attach event listeners for delete, edit buttons, and form submission.
     */
    initEvents() {
        $(document).on('click', '.delete-post', (e) => this.handleDelete(e));
        $(document).on('click', '.edit-post', (e) => this.handleEdit(e));
        $('#postForm').on('submit', (e) => this.handleFormSubmit(e));
    }

    /**
     * Initialize modal behavior: clear validation errors and reset form on modal close.
     */
    initModal() {
        $('#postModal').on('hidden.bs.modal', () => {
            this.clearValidationErrors();
            this.resetForm();
        });
    }
  
    /**
     * Handle delete button click: confirm, send DELETE request, and update UI on success.
     */
    handleDelete(e) {
        e.preventDefault();
        const $button = $(e.currentTarget);
        const postId = $button.data('id');

        if (!confirm('Are you sure you want to delete this post?')) return;

        $('#tableLoadingOverlay').show();

        $.ajax({
            url: `/posts/${postId}`,
            type: 'DELETE',
            dataType: 'json',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .done(() => {
            ToastManager.show('Post deleted successfully');
            this.refreshTable();
        })
        .fail((xhr) => {
            const message = xhr.responseJSON?.message || 'Error deleting post';
            ToastManager.show(message, 'danger');
        })
        .always(() => { $('#tableLoadingOverlay').hide(); });
    }

  
    /**
     * Handle edit button click: either load post data for editing or open empty form for creation.
     */
    handleEdit(e) {
        e.preventDefault();
        const $button = $(e.currentTarget);
        const postId = $button.data('id');
        const action = $button.data('action');
        
        if (action === 'edit') {            
            $.ajax({
                url: `/posts/${postId}`,
                type: 'GET',
                dataType: 'json'
            })
            .done((data) => this.populateEditForm(data))
            .fail((xhr) => {
                const message = xhr.responseJSON?.message || 'Error loading post data';
                ToastManager.show(message, 'danger');
            });
            return;
        }

        this.resetForm();
        $('#postModal').modal('show');
    }
    
    /**
     * Populate the form fields with existing post data for editing.
     * Formats datetime to input-friendly ISO string.
     */
    populateEditForm(postData) {
        $('#modalTitle').text('Edit Post');
        $('#formMethod').val('PUT');
        $('#postId').val(postData.id);
        $('#person_base_id').val(postData.person_base_id);
        $('#content').val(postData.content);

        const formattedDate = postData.created_at.replace(' ', 'T').slice(0, 16);
        $('#created_at').val(formattedDate);
        
        $('#postModal').modal('show');
    }
    
    /**
     * Reset form to initial state for creating a new post and clear validation errors.
     */
    resetForm() {
        $('#modalTitle').text('Create Post');
        $('#formMethod').val('POST');
        $('#postId').val('');
        $('#postForm')[0].reset();
        this.clearValidationErrors();
    }
    
    /**
     * Handle form submission for both creating and updating posts via AJAX.
     * Serializes form data and sends POST or PUT request accordingly.
     */
    handleFormSubmit(e) {
        e.preventDefault();

        this.setFormLoading(true);

        // Serialize form inputs to object
        const formData = $('#postForm').serializeArray();
        const postData = {};
        formData.forEach(item => {
            postData[item.name] = item.value;
        });

        const postId = $('#postId').val();
        const method = $('#formMethod').val();
        const url = postId ? `/posts/${postId}` : '/posts';
        
        $.ajax({
            url: url,
            type: method,
            data: postData,
            dataType: 'json',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .done((data) => this.handleFormSuccess(data, method))
        .fail((xhr) => this.handleFormError(xhr))
        .always(() => this.setFormLoading(false));
    }
    
    /**
     * Called on successful form submission.
     * Shows success toast, hides modal, and refreshes the posts table.
     */
    handleFormSuccess(data, method) {
        ToastManager.show(data.message);
        $('#postModal').modal('hide');
        this.refreshTable();
    }

    /**
     * Refresh posts table and pagination with current filter parameters via AJAX.
     */
    refreshTable() {
        $('#tableLoadingOverlay').show();

        const currentPage = $('#pageInput').val() || 1;
        // Ensure page param is kept when refreshing
        // Probably should go to the page the post is located in but since we can edit theoretically immutable
        // created_at DateTime and we can create with past date it seems a bit silly to do anything but preserve page here as random jumps
        // seemingly will kill UX
        const params = $('#filterForm').serialize() + `&page=${currentPage}`;
        
        $.ajax({
            url: `/posts?${params}`,
            type: 'GET',
            dataType: 'json',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .done((data) => {
            if (data.table) {
                $('.posts-table').html(data.table);
            }
            if (data.pagination) {
                $('.pagination-container').replaceWith(data.pagination);
            }
        })
        .fail((xhr) => {
            const message = xhr.responseJSON?.message || 'Error refreshing posts';
            ToastManager.show(message, 'danger');
        })
        .always(() => $('#tableLoadingOverlay').hide());
    }

    /**
     * Handle errors from form submission.
     * If validation errors are present, display them inline, otherwise show unexpected errors in a toast.
     */
    handleFormError(xhr) {
        if (xhr.status === 422 && xhr.responseJSON?.errors) {
            this.displayValidationErrors(xhr.responseJSON.errors);

            return;
        }

        const message = xhr.responseJSON?.message || 'An error occurred';
        ToastManager.show(message, 'danger');
    }

    /**
     * Display validation errors by highlighting inputs and showing messages.
     */
    displayValidationErrors(errors) {
        this.clearValidationErrors();

        $.each(errors, (field, messages) => {
            const $input = $(`#${field}`);
            const $errorElement = $(`#${field}_error`);
            
            if ($input.length && $errorElement.length) {
                $input.addClass('is-invalid');
                const errorText = Array.isArray(messages) 
                    ? messages.join(', ') 
                    : String(messages);
                $errorElement.text(errorText).addClass('show-error');
            }
        });
        
        // Focus first invalid input
        $('.is-invalid').first().focus();
    }
    
    /**
     * Clear all validation error styles and messages.
     */
    clearValidationErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').removeClass('show-error').text('');
    }
    
    /**
     * Toggle submit button loading state: disable/enable, show spinner, and update text.
     */
    setFormLoading(isLoading) {
        const $submitButton = $('#submitButton');
        const $spinner = $submitButton.find('.spinner-border');
        const $submitText = $submitButton.find('.submit-text');
        
        $submitButton.prop('disabled', isLoading);
        $spinner.toggleClass('d-none', !isLoading);
        $submitText.text(isLoading ? 'Saving...' : 'Save changes');
    }
}

new CrudManager();
