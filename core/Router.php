<?php

namespace Core;

use Core\Attribute\Route;
use ReflectionClass;
use ReflectionMethod;

/**
 * Simple attribute-based router.
 *
 * Features:
 *  - Registers controllers and methods with #[Route] attributes
 *  - Supports dynamic parameters in routes, e.g., /posts/{id}
 *  - Injects Request object if type-hinted in controller action
 */
class Router 
{
    private array $routes = [];

    public function __construct() {
        $this->registerControllers();
    }

    /**
     * Registers all controllers.
     * Could be extended to auto-discover controllers dynamically.
     */
    private function registerControllers(): void {
        $controllers = [
            \App\Controllers\HomeController::class,
            \App\Controllers\PostController::class,
        ];

        foreach ($controllers as $controller) {
            $this->registerController($controller);
        }
    }

    /**
     * Registers all routes from a single controller using reflection.
     * @param string $controllerClass
     */
    private function registerController(string $controllerClass): void {
        $reflection = new ReflectionClass($controllerClass);
        
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(Route::class);
            
            foreach ($attributes as $attribute) {
                /** @var Route $route */
                $route = $attribute->newInstance();
                $this->routes[$route->method][$route->path] = [
                    'controller' => $controllerClass,
                    'action' => $method->getName()
                ];
            }
        }
    }

    /**
     * Dispatches the current request to the appropriate controller/action.
     */
    public function dispatch(): void {
        $request = new Request();
        $method = $request->getMethod();
        $uri = $request->getUri();

        // Direct match
        if (isset($this->routes[$method][$uri])) {
            $this->callAction($request, $this->routes[$method][$uri]);
            return;
        }

        // Match dynamic routes with parameters
        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $route);
            $pattern = "#^$pattern$#";

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $request->setRouteParams($params);
                
                $this->callAction($request, $handler);
                return;
            }
        }

        // Route not found: return 404
        http_response_code(404);
        $viewPath = __DIR__ . '/../app/views/pages/error_404.php';

        if (file_exists($viewPath)) {
            include $viewPath;
            return;
        }

        echo '404 Not Found';
    }

    /**
     * Calls a controller action, injecting Request and route parameters if needed.
     * 
     * @param Request $request
     * @param array{controller: string, action: string} $handler
     */
    private function callAction(Request $request, array $handler): void {
        $controller = new $handler['controller']();
        $action = $handler['action'];

        $methodParams = (new ReflectionMethod($controller, $action))->getParameters();
        $args = [];

        foreach ($methodParams as $param) {
            $type = $param->getType();
            if ($type && $type->getName() === Request::class) {
                $args[] = $request;
                continue;
            }
            
            $args[] = $request->getRouteParam($param->getName());
        }

        $controller->$action(...$args);
    }
}
