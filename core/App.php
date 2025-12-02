<?php

class App
{
    private $routes = [];
    private $middlewares = [];

    // Registrar middleware global
    public function use(callable $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    // Registrar rota
    public function addRoute(string $method, string $path, callable $handler)
    {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function get(string $path, callable $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    // Agrupamento com prefix
    public function prefix(string $prefix)
    {
        return new RouteGroup($this, $prefix);
    }

    // Executar router
    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            $pattern = preg_replace('#:([\w]+)#', '([^/]+)', $route['path']);
            $pattern = "#^" . $pattern . "$#";

            if ($method === $route['method'] && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // remove full match
                $params = $this->extractParams($route['path'], $matches);

                $req = ['params' => $params];
                $res = new Response();

                // Executa middlewares
                foreach ($this->middlewares as $mw) {
                    $mw($req, $res);
                }

                // Executa handler
                $route['handler']($req, $res);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    private function extractParams(string $path, array $matches)
    {
        preg_match_all('#:([\w]+)#', $path, $keys);
        $params = [];
        foreach ($keys[1] as $i => $key) {
            $params[$key] = $matches[$i] ?? null;
        }
        return $params;
    }
}
