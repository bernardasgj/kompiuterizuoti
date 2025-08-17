<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Management System | Posts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/posts.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/table.css">
    <link rel="stylesheet" href="assets/css/pagination.css">

    <link rel="shortcut icon" href="assets/images/favicon_tail.png">
</head>
<body>
    <div class="container py-4">
        <header class="mb-4">
            <h1 class="display-4 text-center">Post Management System</h1>
        </header>

        <main>
            <div class="container">
                <!-- Add Post Button -->
                <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#postModal" data-action="create">
                    <i class="fas fa-plus me-2"></i>Create Post
                </button>

                <?php include('App/Views/components/filter.php')?>
                <div class="posts-table">
                    <?php include('App/Views/components/table.php')?>
                </div>
                <div class="posts-pagination">
                    <?php include('App/Views/components/pagination.php')?>
                </div>
                <?php include('App/Views/components/modal.php')?>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/PaginationManager.js"></script>
    <script src="assets/js/DateRangeValidator.js"></script>
    <script src="assets/js/ToastManager.js"></script>
    <script src="assets/js/CrudManager.js"></script>
    <script src="assets/js/FilterManager.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
