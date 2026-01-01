<?php
namespace Src;

class Dispatcher {
    private $routing = [
        '/'             => ['GalleryController', 'index'],
        '/upload'       => ['GalleryController', 'upload'],
        '/register'     => ['UserController', 'register'],
        '/login'        => ['UserController', 'login'],
        '/logout'       => ['UserController', 'logout'],
        '/cart/add'     => ['GalleryController', 'addToCart'],
        '/cart/clear'   => ['GalleryController', 'clearCart'],
        '/selected'     => ['GalleryController', 'showSelected'],
        '/search'       => ['GalleryController', 'search']
    ];

    public function dispatch($action_url) {
        $action_url = '/' . ltrim($action_url, '/');
        $url_parts = explode('?', $action_url);
        $path = $url_parts[0];

        if (array_key_exists($path, $this->routing)) {
            $controllerName = 'Src\\Controllers\\' . $this->routing[$path][0];
            $methodName = $this->routing[$path][1];

            $controller = new $controllerName();
            $controller->$methodName();
        } else {
            http_response_code(404);
            echo "404 - Strona nie istnieje";
        }
    }
}