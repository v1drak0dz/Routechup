<?php

require_once './core/App.php';
require_once './core/Group.php';
require_once './core/Response.php';

$app = new App();

$app->get("/", function () {
    echo "Hello World";
});

$app->run();
