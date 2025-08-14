<!-- Post Modal -->
<div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Create Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="postForm" method="POST" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="id" id="postId">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="mb-3">
                        <label for="person_base_id" class="form-label">Author</label>
                        <select class="form-select" id="person_base_id" name="person_base_id" required>
                            <option value="">Select author</option>
                            <?php foreach ($persons as $person): ?>
                                <option value="<?= $person->getBaseId() ?>">
                                    <?= $person->getFullName() ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback" id="person_base_id_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="6" required></textarea>
                        <div class="invalid-feedback" id="content_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="created_at" class="form-label">Date</label>
                        <input type="datetime-local" class="form-control" id="created_at" name="created_at" required>
                        <div class="invalid-feedback" id="created_at_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitButton">
                        <span class="submit-text">Save changes</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
