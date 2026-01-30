<?php

$posts = [];
$totalPosts = 0;
$page = max(1, (int) ($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;
$term = "";
$title = "Enter your query";

if (!empty($_GET['s'])) {
    $term = trim($_GET['s']);

    $res = search($term, $limit, $offset, $pdo);
    $posts = $res['posts'];
    $totalPosts = $res['total'];

    $title = 'Search Results for "' . $term . '"';
}

$term = htmlspecialchars($term, ENT_QUOTES, 'UTF-8');
$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

?>


<!DOCTYPE html>
<html lang="en-US">

<?php

$keywords = "Deadtoonsindia, best website to download anime in hindi, deadtoons, dead toons, dead toons india, anime in hindi, watch free anime in hindi";

require_once "inc/head.php"
    ?>

<body class="home blog wp-embed-responsive herald-boxed herald-v_2_6_2">

    <?php
    require('inc/header.php');
    ?>

    <div id="content" class="herald-site-content herald-slide">

    <?php require('inc/components/alert.html') ?>

        <div class="herald-section container ">

            <div class="row">

                <div class="herald-module col-mod-main herald-main-content col-lg-9 col-md-9">



                <div class="herald-mod-wrap">
			        <div class="herald-mod-head ">
			            <div class="herald-mod-title">
			                <h1 class="h6 herald-mod-h herald-color"><?= $title ?></h1>
		                </div>
	                </div>
	                <div class="herald-mod-desc">
	                    <form class="herald-search-form" action="/search" method="get">
                        	<input name="s" class="herald-search-input" type="text" value="<?= $term ?>" placeholder="Type here to search Anime...">
                        	<button type="submit" class="herald-search-submit"></button><br>
                        </form>
                    </div>
                </div>

                    <div class="row row-eq-height herald-posts">


                        <?php
                        
                        foreach ($posts as $a) {
                            article($a, ((isset($_GET['genres']) ? "genres" : "category")), false);
                        }

                        ?>

                    </div>
                    <?php
                    echo pagination($totalPosts, $page, $limit);
                    ?>
                </div>

                <?php require('inc/sidebar-right.php'); ?>

            </div>

        </div>



    </div>

    <?php

    require('inc/footer.php');

    ?>

</body>

</html>