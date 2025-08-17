<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-container">
            <div id="tableLoadingOverlay" class="text-center py-4">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Author</th>
                        <th>Content</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                        <tr data-id="<?= $post->getId() ?>">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= htmlspecialchars($post->_getPerson()->getFullName()) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($post->_getPerson()->_getGroup()?->getName()) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= nl2br(htmlspecialchars(substr($post->getContent(), 0, 50) . (strlen($post->getContent()) > 50 ? '...' : ''))) ?></td>
                            <td><?= $post->getCreatedAt()->format('Y-m-d H:i:s') ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-post" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#postModal"
                                        data-id="<?= $post->getId() ?>"
                                        data-action="edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-post" 
                                        data-id="<?= $post->getId() ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No posts</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
