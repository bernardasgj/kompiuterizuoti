<?php

namespace Core;

use RuntimeException;
use DateTime;

class Request {
    private array $queryParams;
    private array $requestData;
    private array $routeParams;
    private string $method;
    private string $uri;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->queryParams = $_GET;
        $this->requestData = $this->parseRequestData();
        $this->routeParams = [];
    }

    private function parseRequestData(): array {
        if ($this->method === 'POST') {
            return $_POST;
        }

        $input = file_get_contents('php://input');
        if (str_starts_with($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
            return json_decode($input, true) ?? [];
        }

        parse_str($input, $data);
        return $data ?? [];
    }

    public function getQueryParams(): array {
        return $this->queryParams;
    }

    public function getQueryParam(string $key, $default = null) {
        return isset($this->queryParams[$key]) && $this->queryParams[$key] !== ''
            ? $this->queryParams[$key]
            : $default;
    }

    public function getRequestData(): array {
        return $this->requestData;
    }

    public function get(string $key, $default = null) {
        return $this->requestData[$key] ?? $default;
    }

    public function getRouteParams(): array {
        return $this->routeParams;
    }

    public function getRouteParam(string $key, $default = null) {
        return $this->routeParams[$key] ?? $default;
    }

    public function setRouteParams(array $params): void {
        $this->routeParams = $params;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getUri(): string {
        return $this->uri;
    }

    public function getIntQueryParam(string $key, ?int $default = null, bool $positive = true): ?int {
        $value = $this->getQueryParam($key);
        if ($value === null) {
            return $default;
        }

        if (!ctype_digit((string)$value)) {
            throw new RuntimeException("Invalid parameter '{$key}': must be an integer, given '{$value}'");
        }

        $intVal = (int)$value;
        if ($positive && $intVal <= 0) {
            throw new RuntimeException("Invalid parameter '{$key}': must be a positive integer, given '{$value}'");
        }
        return $intVal;
    }

    public function getDateQueryParam(
        string $key,
        ?string $default = null,
        string $format = 'Y-m-d'
    ): ?string {
        $value = $this->getQueryParam($key) ?? $default;
    
        if ($value === null) {
            return null;
        }
    
        $patterns = [
            'Y-m-d' => '/^\d{4}-\d{2}-\d{2}$/',
        ];
    
        $regex = $patterns[$format] ?? null;
        if (!$regex) {
            throw new RuntimeException("Unsupported date format '{$format}'");
        }
    
        if (!preg_match($regex, $value)) {
            throw new RuntimeException("Invalid parameter '{$key}': must be in format {$format}, given '{$value}'");
        }
    
        return $value;
    }
}
