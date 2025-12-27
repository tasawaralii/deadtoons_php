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


					<?php
					if (isset($_GET['s'])) {

						echo '<div class="herald-mod-wrap">
			        <div class="herald-mod-head ">
			            <div class="herald-mod-title">
			                <h1 class="h6 herald-mod-h herald-color">Search Results For - ' . $_GET['s'] . '</h1>
		                </div>
	                </div>
	                <div class="herald-mod-desc">
	                    <form class="herald-search-form" action="' . LINK . '" method="get">
                        	<input name="s" class="herald-search-input" type="text" value="" placeholder="Type here to search Anime...">
                        	<button type="submit" class="herald-search-submit"></button><br>
                        </form>
                    </div>
                </div>';

					} else if (!$home) {

						echo '<div class="herald-mod-wrap">
			        <div class="herald-mod-head herald-cat-14">
			            <div class="herald-mod-title">
			                <h1 class="h6 herald-mod-h herald-color">' . $type . ' - ' . $value . '</h1>
		                </div>
	                </div>';

						if ($type == "author") {

							echo '<div class="herald-mod-desc">
    			        <p><img alt="" src="' . get_gravatar_url($posts[0]['author_email'], 140) . '" class="avatar avatar-80 photo" height="80" width="80" decoding="async">' . $posts[0]['author_quote'] . '</p>
                    </div>';

						}
						echo '</div>';

					}
					?>
					<div class="row row-eq-height herald-posts">


						<?php

						foreach ($featuredPosts as $a) {
							article($a, $site['link'] . "/", "category", true);
						}

						foreach ($posts as $a) {
							article($a, $site['link'] . "/", ((isset($_GET['genres']) ? "genres" : "category")), false);
						}

						?>

					</div>
					<?php
					echo pagination($totalPosts, $page, $limit, "", ((isset($_GET['s']) ? "?s=" . $_GET['s'] : '')), $cat);
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