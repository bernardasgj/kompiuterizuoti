<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" class="row g-3">
            <input type="hidden" name="page" id="pageInput" value="<?= $currentPage ?>">
            <input type="hidden" name="per_page" id="perPageInput" value="<?= $perPage ?>">

            <div class="col-md-3">
                <label for="group_id" class="form-label">Group</label>
                <select class="form-select" id="group_id" name="group_id">
                    <option value="">All Groups</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?=$group->getId()?>" <?= $group->getId() == $currentGroupId ? 'selected' : '' ?>>
                            <?= $group->getName() ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" class="form-control date-range" id="from_date" name="from_date" 
                       value="<?= htmlspecialchars($fromDate ?? '') ?>" 
                       data-range-target="#to_date" data-range-type="min">
            </div>

            <div class="col-md-3">
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" class="form-control date-range" id="to_date" name="to_date" 
                       value="<?= htmlspecialchars($toDate ?? '') ?>" 
                       data-range-target="#from_date" data-range-type="max">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>
