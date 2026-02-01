<?php

$posts = [];
$totalPosts = 0;
$page = max(1, (int) ($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;
$featuredPosts = [];
$featuredPostIds = [];

if ($page == 1) {
	$featuredPosts = featured($pdo);
	$featuredPostIds = array_column($featuredPosts, 'id');
}

$posts = posts($featuredPostIds, $limit, $offset, $pdo);
$totalPosts = totalPosts($pdo);

?>

<!DOCTYPE html>
<html lang="en-US">

<?php

$keywords = "Deadtoonsindia, best website to download anime in hindi, deadtoons, dead toons, dead toons india, anime in hindi, watch free anime in hindi";
$title = "Dead Toons India - Best website to Download Anime in Hindi";
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

					<div class="row row-eq-height herald-posts">


						<?php

						foreach ($featuredPosts as $a) {
							article($a, "category", true);
						}

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