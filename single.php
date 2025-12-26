<?php
$ispost = $post['post_type'] == "post";
$anime_name = explode(" (", $post['title']);
$anime_name = trim($anime_name[0]);
$title = $post['title'] . " - Dead Toons India";
$keywords = "$anime_name, $anime_name anime in hindi, $anime_name hindi dubbed download, $anime_name watch free in hindi,anime in hindi";
?>
<!DOCTYPE html>
<html lang="en-US">

<?php
require_once "inc/head.php"
?>

<body
	class="post-template-default single single-post postid-<?php echo $post['id'] ?> single-format-standard wp-embed-responsive herald-boxed herald-v_2_6_2">

	<?php
	require('inc/header.php');
	?>

	<div id="content" class="herald-site-content herald-slide">

		<div class="herald-section container ">

			<article id="post-<?php echo $post['id'] ?>"
				class="herald-single post-<?php echo $post['id'] ?> post type-post status-publish format-standard has-post-thumbnail hentry">
				<div class="row">


					<div class="col-lg-9 col-md-9 col-mod-single col-mod-main">

						<header class="entry-header">
							<span class="meta-category">

								<?php
								$cat_slugs = $post['cat_slugs'] ? explode(',', $post['cat_slugs']) : [];
								$cat_names = $post['cat_names'] ? explode(',', $post['cat_names']) : [];
								$cat_html = [];
								foreach ($cat_slugs as $index => $cat_slug) {
									$cat_name = $cat_names[$index];
									if (!$cat_name)
										continue;
									$cat_html[] = "<a href=" . $site['link'] . "/category/{$cat_slug}/ class='herald-cat-{$cat_slug}'>{$cat_name}</a>";
								}
								echo implode('<span> &bull; </span>', $cat_html);
								?>

								<?php
								if (!$ispost) {
									echo '<center><h1 class="entry-title h1">' . $post['title'] . '</h1></center><br><br>';
								} else {
									echo '<h1 class="entry-title h1">' . $post['title'] . '</h1>';
								}
								?>
								<?php
								if ($ispost) { ?>
									<div class="entry-meta entry-meta-single">
										<div class="meta-item herald-date">
											<span class="updated"><?php echo time_elapsed_string($post['pubDate']) ?></span>
										</div>
									</div>
							</header>
							<div class="herald-post-thumbnail herald-post-thumbnail-single">
								<span><img width="780" height="439"
										src="<?= IMAGE_DOMAIN . "/" . $post['file_path'] ?>"
										class="attachment-herald-lay-single size-herald-lay-single wp-post-image"
										alt="<?php echo $post['title'] ?>" /></span>
							</div>
						<?php } ?>
						<div class="row">



							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="entry-content herald-entry-content">

									<?php
									// 	echo $post['content'];
									

									$parsed_content = parse_shortcodes($post['content']);

									echo $parsed_content;

									?>
									<?php
									if ($post['tag_slugs'] != '' || $post['tag_slugs'] != null) {
										echo '<div class="meta-tags">';
										echo '<span>Tags</span>';
										$tag_slugs = explode(',', $post['tag_slugs']);
										$tag_names = explode(',', $post['tag_names']);
										foreach ($tag_slugs as $index => $tag_slug) {
											$tag_name = $tag_names[$index];
											echo "<a href='" . $site['link'] . "/tag/{$tag_slug}/' rel='tag'>{$tag_name}</a> ";
										}
										echo '</div>';
									}
									?>

								</div>
							</div>

							<div id="extras" class="col-lg-12 col-md-12 col-sm-12">



								<?php
								if ($ispost) {
									require('inc/related.php');
									author($post['author_email'], $post['author_display_name'], $post['author_slug'], $post['author_quote']);
									$com_post_id = $post['id'];
									require('comments.php');
								}
								?>


							</div>

						</div>

					</div>


					<?php
					require('inc/sidebar-right.php');
					?>


				</div>
			</article>
		</div>


	</div>


	<?php
	require('inc/footer.php');
	?>
	<a href="javascript:void(0)" id="back-top" class="herald-goto-top"><i class="fa fa-angle-up"></i></a>

	<script type="text/javascript" src="<?php echo $site['link'] . "/js/imagesloaded.min.js" ?>"
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


	<script type="text/javascript" src="<?php echo $site['link'] . "/js/min.js" ?>" id="herald-main-js"></script>

</body>

</html>