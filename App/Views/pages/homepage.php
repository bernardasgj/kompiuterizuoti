<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Management System | Description</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/images/favicon_tail.png">
</head>
<body>
    <div class="container py-5">
        <header class="mb-5 text-center">
            <h1 class="display-4 fw-bold">
                <i class="fas fa-comments text-primary me-2"></i>Post Management System
            </h1>
            <p class="lead text-muted">A clean, minimal MVC-based application for managing, filtering, and creating posts via AJAX.</p>
        </header>

        <main>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h3 class="mb-3">📋 Project Overview</h3>
                            <p>
                                This project is a <strong>technical assessment task</strong> implementing a simple yet functional post management system.
                                Built with <em>vanilla PHP</em>, a custom MVC structure, and <em>jQuery</em> for interactivity. It allows users to:
                            </p>
                            <ul>
                                <li>View all posts in reverse chronological order.</li>
                                <li>Gives filters: group, from_date and to_date.</li>
                                <li>Post object CRUD implemented via AJAX.</li>
                            </ul>

                            <h4 class="mt-4">✅ Key Features</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul>
                                        <li>Group & date-based filtering</li>
                                        <li>Pagination</li>
                                        <li>AJAX post creation (no page reload)</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul>
                                        <li>AJAX edit/delete</li>
                                        <li>Bootstrap 5 UI</li>
                                    </ul>
                                </div>
                            </div>

                            <h4 class="mt-4">🛠 Technical Stack</h4>
                            <p>
                                <strong>Backend:</strong> PHP 8, MySQL<br>
                                <strong>Frontend:</strong> Bootstrap 5, jQuery, Font Awesome<br>
                            </p>

                            <div class="text-center mt-4">
                                <a href="/posts" class="btn btn-primary btn-lg">
                                    <i class="fas fa-arrow-right me-2"></i>Go to Posts
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <small class="text-muted">2025 Post Management System</small>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
