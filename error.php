<?php
http_response_code(404);
?>

<!DOCTYPE html>
<html lang="en-US">

<?php

$title = "Not Found";
$keywords = DESCRIPTION;

require_once "inc/head.php"

	?>

<body class="error404 wp-embed-responsive herald-boxed herald-v_2_6_2">



	<?php require('inc/header.php'); ?>

	<div id="content" class="herald-site-content herald-slide">



		<div class="herald-section container herald-no-sid">

			<article class="herald-page">

				<div class="row">
					<div class="herald-module col-lg-12 col-md-12 col-mod-single">

						<div class="herald-ovrld">

							<header class="entry-header">
								<h1 class="entry-title h1">404 error: Page not found</h1>
							</header>

							<div class="herald-page-thumbnail">

								<span>
									<img src="/public/images/default.png" />
								</span>
							</div>
						</div>

						<div class="col-lg-9 col-md-9 col-mod-single">
							<div class="entry-content herald-entry-content">
								<h3 class="entry-title">What is happening?</h3>
								<p>The page that you are looking for does not exist on this website. You may have
									accidentally mistype the page address, or followed an expired link. Anyway, we will
									help you get back on track. Why not try to search for the page you were looking for:
								</p>
								<form class="herald-search-form" action="" method="get">
									<input name="s" class="herald-search-input" type="text" value=""
										placeholder="Type here to search..." /><button type="submit"
										class="herald-search-submit"></button>
								</form>
							</div>
						</div>

					</div>
				</div>

			</article>
		</div>



	</div>


	<?php require('inc/footer.php'); ?>
	<a href="javascript:void(0)" id="back-top" class="herald-goto-top"><i class="fa fa-angle-up"></i></a>


	<script type="text/javascript" src="/js/imagesloaded.min.js?ver=5.0.0" id="imagesloaded-js"></script>
	<script type="text/javascript" id="herald-main-js-extra">
		/* <![CDATA[ */
		var herald_js_settings = { "rtl_mode": "false", "header_sticky": "1", "header_sticky_offset": "600", "header_sticky_up": "", "single_sticky_bar": "", "popup_img": "1", "logo": "\/public\/images\/logo.png", "logo_retina": "\/public\/images\/logo.png", "logo_mini": "\/public\/images\/logo.png", "logo_mini_retina": "\/public\/images\/logo.png", "smooth_scroll": "1", "trending_columns": "6", "responsive_menu_more_link": "", "header_ad_responsive": "", "header_responsive_breakpoint": "1249" };
		/* ]]> */
	</script>
	<script type="text/javascript" src="/js/min.js?ver=2.6.2" id="herald-main-js"></script>

</body>

</html>