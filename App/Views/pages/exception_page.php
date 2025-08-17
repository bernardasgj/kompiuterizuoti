<?php
/** @var string $title */
/** @var string $message */
/** @var string|null $file */
/** @var int|null $line */
/** @var string|null $trace */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8d7da; font-family: system-ui, sans-serif; padding: 3rem; }
        .error-card { max-width: 800px; margin: auto; border-left: 5px solid #dc3545; border-radius: 12px; }
        .error-header h1 { font-size: 4rem; color: #dc3545; font-weight: bold; }
        .btn-home { background-color: #dc3545; border: none; }
        .btn-home:hover { background-color: #b02a37; }
        .trace { font-family: monospace; white-space: pre-wrap; overflow-x: auto; background: #f1f3f5; border-radius: 5px; padding: 1rem; border: 1px solid #dee2e6; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <header class="text-center mb-4 error-header">
            <h1>⚠️ <?= htmlspecialchars($title) ?></h1>
        </header>

        <div class="card shadow-sm error-card">
            <div class="card-body">
                <h3 class="card-title text-danger">
                    <i class="fas fa-triangle-exclamation me-2"></i><?= htmlspecialchars($title) ?>
                </h3>
                <p class="card-text"><?= htmlspecialchars($message) ?></p>
                <?php if ($file): ?>
                    <p><strong>File:</strong> <?= htmlspecialchars($file) ?><?php if ($line): ?> (line <?= $line ?>)<?php endif; ?></p>
                <?php endif; ?>
                <?php if ($trace): ?>
                    <div class="trace"><?= htmlspecialchars($trace) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
