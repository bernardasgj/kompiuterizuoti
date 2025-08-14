<?php

namespace Core;

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
        return isset($this->queryParams[$key]) && $this->queryParams[$key] ? $this->queryParams[$key] : $default;
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
}
