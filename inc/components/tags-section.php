<?php

$tags = json_decode($post['tags'], true);
$tag_links = array_map(fn($t) => "<a href='{$t['slug']}/' rel='tag'>{$t['name']}</a>", $tags);

if (count($tags) > 0) {
    echo '<div class="col-lg-12 col-md-12 col-sm-12" style="margin-bottom:25px;">';
    echo '<div class="meta-tags">';
    echo '<span>Tags</span>';
    echo implode(' ', $tag_links);
    echo '</div>';
    echo "</div>";
}
?>