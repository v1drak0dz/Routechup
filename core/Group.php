<?php

class RouteGroup
{
    private $app;
    private $prefix;

    public function __construct(App $app, string $prefix)
    {
        $this->app = $app;
        $this->prefix = $prefix;
    }

    public function get(string $path, callable $handler)
    {
        $this->app->get($this->prefix . $path, $handler);
    }

    public function post(string $path, callable $handler)
    {
        $this->app->post($this->prefix . $path, $handler);
    }
}
