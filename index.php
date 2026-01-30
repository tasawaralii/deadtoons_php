<?php

require_once('env.php');
require_once('src/Router.php');
require_once 'config.php';
require_once 'functions.php';

$router = new Router();

$router->get("/", "home.php");
$router->get("/search", "search.php");
$router->get("/genre/{genre}", "genre.php");
$router->get("/category/{category}", "category.php");
$router->get("/author/{author}", "author.php");
$router->get("/{slug}", "single.php");

$router->resolve();