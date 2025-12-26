<?php

require_once("functions.php");
require_once("db.php");
require_once("site_info.php");
require_once('config.php');

$uri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($uri);
$path = $parsedUrl['path'] ?? '';
$query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

if (substr($path, -1) !== '/') {
    $newUrl = $path . '/' . $query;
    header("Location: $newUrl", true, 301);
    exit;
}

if($path == "/" || preg_match('#^/page/\d+/$#',$path) || isset($_GET['s']) || preg_match('#/(category|genres|author|tag)/(.*?)/#', $path, $matches)) {
    
        
    $featuredPosts = [];
    $featuredPostIds = [];
    
    $posts = [];
    $totalPosts = 0;
    $cat = "";
    $title = "";
    $page = 1;
    $limit = 10;
    $canonical = "/";
    $home = false;
    
    
    if(preg_match('#page/(\d+)#',$path,$pageNumber)) {
        $page = (int)$pageNumber[1];
    }
    
    
    $offset = ($page-1)*$limit;
    
    if(isset($_GET['s'])) {
             
        $term = $_GET['s'];
        
      	$res = search($term, $limit, $offset, $pdo);
       	
    	$title = 'Search Results for "'.$term.'"';
        	
    } else if($path == "/" || preg_match('#^/page/\d+/$#',$path)) {
        
        $home = true;
        
        $res = [
            'posts' => posts(false , $limit, $offset, $pdo),
            'total' => totalPosts($pdo)
        ];
        
        if($path == "/") {
            $featuredPosts = featured($pdo);
            $featuredPostIds = array_column($featuredPosts, 'id');
        }
        
    } else {
        
        $type = $matches[1];
        $value = $matches[2];
        
        if($type == "category") {
            
            $res = category($value, $limit, $offset, $pdo);
    
        	$cat = "/category/".$value;
        	
        	$title = $value;
        	
        	$canonical .= "/category/".$value."/";
        	
        } else if($type == "genres") {
            
            $res = genre($value, $limit, $offset, $pdo);
            
        	$cat = "/genres/".$value;
        	
        	$title = $value;
        	
        	$canonical .= "/genres/".$value."/";
        } else if($type == "author") {
            
            $res = author_posts($value, $limit, $offset, $pdo);
            
        	$cat = "/author/".$value;
            
        	$title = $res['posts'][0]['author_display_name'];
        	
        	$canonical .= "/author/".$value."/";
        	
        } else if($type == "tag") {
        	$res = tag($value, $limit, $offset, $pdo);
        	
        	$cat = "/tag/".$value;
        	
        	$title = $value;
        	
        	$canonical .= "/tag/".$value."/";
        }
        
    }
    
    	$posts = $res['posts'];
    	$totalPosts = $res['total'];
    	
    	require_once("home.php");
    	exit;

} else {
    
    
    $slug = trim($path,'/');
    
    $post = single($slug, $pdo);
    
    if(!$post)
        require('error.php');
    else
        require_once('single.php');
    exit;
    
}


// 	$res = search($_GET['s'], $limit, $offset, $pdo);
