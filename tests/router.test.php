<?php

require_once __DIR__ . '/../core/Bootstrap.php';
require_once __DIR__ . '/../lib/CanaTest/Core/TestCase.php';

class RouterTest extends TestCase
{
    public function __construct()
    {
        parent::__construct("Routechup Router");

        $this->test("Register simple GET route", function () {
            $router = new App();
            $router->get("/home", fn() => "Home Page");

            $result = $router->dispatch("GET", "/home");
            Assertions::equal($result, "Home Page", "Should return content of /home route");
        });

        $this->test("Nonexistent route returns 404 Not Found", function () {
            $router = new App();
            $router->get("/home", fn() => "Home Page");

            $result = $router->dispatch("GET", "/about");
            Assertions::equal($result, "404 Not Found", "Nonexistent route should return 404");
        });

        $this->test("Register POST route", function () {
            $router = new App();
            $router->post("/submit", fn() => "Form submitted");

            $result = $router->dispatch("POST", "/submit");
            Assertions::equal($result, "Form submitted", "Should return content of POST /submit route");
        });

        $this->test("Route with dynamic parameter", function () {
            $router = new App();
            $router->get("/user/:id", function ($req, $res) {
                return "User " . $req['params']['id'];
            });

            $result = $router->dispatch("GET", "/user/42");
            Assertions::equal($result, "User 42", "Should capture parameter :id");
        });
    }
}
