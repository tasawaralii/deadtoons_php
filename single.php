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

						<?php require_once "inc/components/post-header.php"; ?>

						<!-- Thumbnail -->
						<?php if ($post['file_path']): ?>

							<div class="herald-post-thumbnail herald-post-thumbnail-single">
								<span>
									<img width="780" height="439" src="<?= IMAGE_DOMAIN . "/" . $post['file_path'] ?>"
										class="attachment-herald-lay-single size-herald-lay-single wp-post-image"
										alt="<?php echo $post['title'] ?>" />
								</span>
							</div>

						<?php endif ?>

						<div class="row">

							<?php require_once "inc/components/post-body.php" ?>
							<?php require_once "inc/components/tags-section.php" ?>

							<div id="extras" class="col-lg-12 col-md-12 col-sm-12">

								<?php
								if ($ispost) {
									require('inc/components/related.php');
									$authorEmail = $post['author_email'];
									$authorName = $post['author_display_name'];
									$authorSlug = $post['author_slug'];
									$authorLine = $post['author_quote'];
									require('inc/components/author-box.php');

									$com_post_id = $post['id'];
									require('inc/components/comments.php');
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


</body>

</html>