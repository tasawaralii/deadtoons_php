<?php

require_once('env.php');
require_once('src/Router.php');
require_once 'config.php';
require_once 'functions.php';
require_once 'dbService.php';

$router = new Router();

$router->post("/post-comment", "ajax/comments-post.php");

$router->get("/", "views/home.php");
$router->get("/search", "views/search.php");
$router->get("/genre/{genre}", "views/genre.php");
$router->get("/category/{category}", "views/category.php");
$router->get("/author/{author}", "views/author.php");
$router->get("/{slug}", "views/single.php");

$router->post("/upload/image", "services/upload.php");
$router->resolve();