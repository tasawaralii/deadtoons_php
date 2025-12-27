<?php
if ($post['tag_slugs'] != '' || $post['tag_slugs'] != null) {
    echo '<div class="col-lg-12 col-md-12 col-sm-12" style="margin-bottom:25px;">';
    echo '<div class="meta-tags">';
    echo '<span>Tags</span>';
    $tag_slugs = explode(',', $post['tag_slugs']);
    $tag_names = explode(',', $post['tag_names']);
    foreach ($tag_slugs as $index => $tag_slug) {
        $tag_name = $tag_names[$index];
        echo "<a href='/tag/{$tag_slug}/' rel='tag'>{$tag_name}</a> ";
    }
    echo '</div>';
    echo "</div>";
}
?>