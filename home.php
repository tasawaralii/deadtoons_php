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

		<div class="alertxxx"> <span class="closebtn" onclick="this.parentElement.style.display='none';">×</span> <b>
				<font size="3px">Bookmark and share our new domain <a href="https://deadtoons.one"
						data-wpel-link="internal" style="color:yellow">Deadtoons.one</a></font>
			</b></div>

		<div class="alertxx"> <span class="closebtn" onclick="this.parentElement.style.display='none';"
				style='color:black'>×</span> <b>
				<font size="3px">🎉🎉 Fixed Pack Download | Added New Servers in Zip Files 🎉🎉</font>
			</b></div>

		<style>
			span.closebtn {
				margin-left: 15px;
				color: white;
				font-weight: bold;
				float: right;
				font-size: 22px;
				line-height: 20px;
				cursor: pointer;
				transition: 0.3s;
			}

			.alertxxx {
				padding: 5px;
				margin-bottom: 20px;
				margin-top: 5px;
				margin-left: auto;
				max-width: 900px;
				margin-right: auto;
				color: #282828;
				background-color: #2cdf9d;
				width: 90%;
				text-align: center;
				border-radius: 6px;
			}

			.alertxx {
				padding: 5px;
				margin-bottom: 20px;
				margin-top: 5px;
				margin-left: auto;
				max-width: 900px;
				margin-right: auto;
				color: #282828;
				background-color: #FFFF00;
				width: 90%;
				text-align: center;
				border-radius: 6px;
			}
		</style>

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

	<a href="javascript:void(0)" id="back-top" class="herald-goto-top"><i class="fa fa-angle-up"></i></a>

	<!-- The popup and overlay -->
	<div id="overlay" class="overlay" style="display: none;"></div>
	<div id="popup" class="popup" style="display: none;">
		<h2>Click Here to Go to Link</h2>
		<button onclick="goToLink()">Click Me</button>
	</div>


	<script type="text/javascript" src="/js/imagesloaded.min.js"
		id="imagesloaded-js"></script>
	<script type="text/javascript" id="herald-main-js-extra">
		/* <![CDATA[ */
		var herald_js_settings = {
			"rtl_mode": "false",
			"header_sticky": "1",
			"header_sticky_offset": "600",
			"header_sticky_up": "",
			"single_sticky_bar": "",
			"popup_img": "1",
			"logo": "<?php echo $site['link'] ?>/content\/2023\/11\/Bnu6Tln.webp",
			"logo_retina": "<?php echo $site['link'] ?>\/content\/2023\/11\/Bnu6Tln.webp",
			"logo_mini": "<?php echo $site['link'] ?>\/content\/2023\/11\/Bnu6Tln.webp",
			"logo_mini_retina": "<?php echo $site['link'] ?>\/content\/2023\/11\/Bnu6Tln.webp",
			"smooth_scroll": "1",
			"trending_columns": "6",
			"responsive_menu_more_link": "",
			"header_ad_responsive": "",
			"header_responsive_breakpoint": "1249"
		};

		/* ]]> */
	</script>
	<script type="text/javascript" src="/js/min.js" id="herald-main-js"></script>

</body>

</html>