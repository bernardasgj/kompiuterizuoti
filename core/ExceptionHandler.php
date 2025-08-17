<?php

namespace Core;


use Throwable;
use ErrorException;

class ExceptionHandler
{
    public static function register(bool $debug = true): void
    {
        set_exception_handler(function (Throwable $e) use ($debug) {
            self::render(
                $e::class,
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $debug ? $e->getTraceAsString() : null
            );
        });

        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        register_shutdown_function(function () use ($debug) {
            $error = error_get_last();
            if ($error !== null) {
                self::render(
                    "💥 Fatal Error",
                    $error['message'],
                    $error['file'],
                    $error['line'],
                    $debug ? null : null
                );
            }
        });
    }

    private static function render(string $title, string $message, ?string $file, ?int $line, ?string $trace = null): void
    {
        http_response_code(500);

        $viewTitle = $title;
        $viewMessage = $message;
        $viewFile = $file;
        $viewLine = $line;
        $viewTrace = $trace;

        require __DIR__ . '/../App/Views/pages/exception_page.php';

        exit;
    }

}
