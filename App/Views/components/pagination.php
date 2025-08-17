<?php if ($totalPosts > 0): ?>
<div class="pagination-container d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted">
        Showing <?= ($currentPage - 1) * $perPage + 1 ?> to <?= min($currentPage * $perPage, $totalPosts) ?> of <?= $totalPosts ?> entries
    </div>
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="#" data-page="<?= $currentPage - 1 ?>">Previous</a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="#" data-page="<?= $currentPage + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="perPageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $perPage ?> per page
        </button>
        <ul class="dropdown-menu" aria-labelledby="perPageDropdown">
            <li><a class="dropdown-item" href="#" data-per-page="5">5 per page</a></li>
            <li><a class="dropdown-item" href="#" data-per-page="10">10 per page</a></li>
            <li><a class="dropdown-item" href="#" data-per-page="20">20 per page</a></li>
            <li><a class="dropdown-item" href="#" data-per-page="50">50 per page</a></li>
        </ul>
    </div>
</div>
<?php endif; ?>
