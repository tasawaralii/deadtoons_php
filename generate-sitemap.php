<?php

require('db.php');

// Define the sitemap file path
$sitemapFile = 'sitemap.xml';

// Base domain
$baseUrl = "https://deadtoons.org";

// Open the file for writing
$handle = fopen($sitemapFile, 'w');

// Start the XML content
$xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xmlContent .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// Add static URLs
$staticUrls = [
    '/',
    '/about/',
    '/privacy-policy/',
    '/category/ongoing/',
    '/category/completed/',
];

foreach ($staticUrls as $url) {
    $xmlContent .= "<url><loc>{$baseUrl}{$url}</loc><priority>1.0</priority></url>" . PHP_EOL;
}

// Fetch categories from the database
$categories = $pdo->query("SELECT cat_slug FROM categories")->fetchAll();
foreach ($categories as $category) {
    $url = $baseUrl . "/category/" . $category['cat_slug']."/";
    $xmlContent .= "<url><loc>{$url}</loc><priority>0.9</priority></url>" . PHP_EOL;
}

// Fetch tags from the database
$tags = $pdo->query("SELECT tag_slug FROM tags")->fetchAll();
foreach ($tags as $tag) {
    $url = $baseUrl . "/tag/" . $tag['tag_slug']."/";
    $xmlContent .= "<url><loc>{$url}</loc><priority>0.8</priority></url>" . PHP_EOL;
}

// Fetch genres from the database
$genres = $pdo->query("SELECT genre_slug FROM genres")->fetchAll();
foreach ($genres as $genre) {
    $url = $baseUrl . "/genres/" . $genre['genre_slug']."/";
    $xmlContent .= "<url><loc>{$url}</loc><priority>0.8</priority></url>" . PHP_EOL;
}

// Fetch dynamic post URLs
$posts = $pdo->query("SELECT slug, pubDate FROM posts")->fetchAll();
foreach ($posts as $post) {
    $url = $baseUrl . "/" . $post['slug']."/";
    $lastMod = date('Y-m-d', strtotime($post['pubDate']));
    $xmlContent .= "<url><loc>{$url}</loc><lastmod>{$lastMod}</lastmod><priority>0.8</priority></url>" . PHP_EOL;
}

// Close the XML tags
$xmlContent .= '</urlset>' . PHP_EOL;

// Write content to the file
fwrite($handle, $xmlContent);

// Close the file handle
fclose($handle);

echo "Sitemap successfully created at {$sitemapFile}";
?>
