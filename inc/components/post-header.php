<header class="entry-header">

    <!-- Categories -->
    <?php

    $categories = json_decode($post['categories'], true);
    $cat_links = array_map(fn($c) => "<a href='{$c['slug']}'>{$c['name']}</a>", $categories);

    if ($cat_links) {
        echo '<span class="meta-category">';
        echo implode('<span> &bull; </span>', $cat_links);
        echo '</span>';
    }
    ?>

    <!-- Title -->
    <?php
    if (!$ispost) {
        echo '<center><h1 class="entry-title h1">' . $post['title'] . '</h1></center><br><br>';
    } else {
        echo '<h1 class="entry-title h1">' . $post['title'] . '</h1>';
    }
    ?>


    <?php
    if ($ispost): ?>
        <div class="entry-meta entry-meta-single">
            <div class="meta-item herald-date">
                <span class="updated"><?php echo time_elapsed_string($post['pubDate']) ?></span>
            </div>
        </div>

    <?php endif ?>
</header>