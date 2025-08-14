<?php

namespace Core;

class Controller {
    /**
     * Renders a view partial and returns it as a string.
     * Used for AJAX responses and views embedded into other views (components).
     *
     * @param string $view The name of the view file (relative to app/views)
     * @param array $data Data to pass to the view
     * @return string The rendered view content
     */
    protected function renderPartial(string $view, array $data = []): string {
        // Extract array keys into variables for use in the view
        extract($data);

        // Start output buffering
        ob_start();

        include(__DIR__ . '/../app/views/' . $view . '.php');

        // Return the captured output
        return ob_get_clean();
    }

    /**
     * Renders a full view and sends it directly to the browser.
     * If the view file does not exist, sends a 404 header and message.
     *
     * @param string $view The name of the view file
     * @param array $data Optional data to pass to the view
     */
    protected function render(string $view, array $data = []) {
        extract($data);

        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
            return;
        }

        // View not found — send 404 response
        header("HTTP/1.0 404 Not Found");
        echo '404 Not Found';
    }

    protected function getRequestData(): array {
        $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        return $data;
    }
    
    protected function sendJsonResponse(int $statusCode, array $data): void {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        return;
    }
    
    protected function isAjaxRequest(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}
