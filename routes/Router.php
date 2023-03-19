<?php

namespace Routes;

use App\Controller\InvoiceController;
use App\Controller\ProductController;
use App\Controller\CustomerController;

class Router {
    private function parseRoute($route)
    {
        return '/' . rtrim(ltrim(trim($route), '/'), '/');
    }

    private function make($route)
    {
        switch ($route) {
            case '':
            case '/home':
            case '/index.php':
            case '/login':
            case '/register':
            case '/dashboard':
            case (preg_match('/invoice.*/', $route) ? true : false):
                return new InvoiceController();
            case (preg_match('/product.*/', $route) ? true : false):
                return new ProductController();
            case (preg_match('/customer.*/', $route) ? true : false):
                return new CustomerController();
        }
    }

    private function handler($route, $request, $function)
    {
        $route = $this->parseRoute($route);
        $controller = $this->make($route);

        // $routeId = explode('/', $route);
        // $request['id'] = end($routeId);

        if (method_exists($controller, $function)) {
            return $controller->$function($request);
        }

        # Return 404 as JSON response
        return json_encode([
            'status' => 413,
            'message' => 'Method not allowed'
        ]);
    }
    
    /**
     * Create route based on the request method
     */
    public function route($route, $method, $request)
    {
        $route = $this->parseRoute($route);

        switch ($method) {
            case 'GET':
                if (preg_match('/\/.+?\/[0-9]+/', $route)) {
                    return $this->handler($route, $request, 'show');
                } else if (preg_match('/\/.+?\/[0-9]+\/json/', $route)) {
                    return $this->handler($route, $request, 'showJson');
                } else if (preg_match('/\/.+?\/json/', $route)) {
                    return $this->handler($route, $request, 'indexJson');
                } else {
                    return $this->handler($route, $request, 'index');
                }
            case 'POST':
                return $this->handler($route, $request, 'store');
            case 'PUT|PATCH':
                return $this->handler($route, $request, 'update');
            case 'DELETE':
                return $this->handler($route, $request, 'destroy');
        }
    }
}
