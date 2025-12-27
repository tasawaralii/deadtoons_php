<header class="entry-header">

    <!-- Categories -->
    <?php
    $cat_slugs = $post['cat_slugs'] ? explode(',', $post['cat_slugs']) : [];
    $cat_names = $post['cat_names'] ? explode(',', $post['cat_names']) : [];
    $cat_html = [];
    foreach ($cat_slugs as $index => $cat_slug) {
        $cat_name = $cat_names[$index];
        if (!$cat_name)
            continue;
        $cat_html[] = "<a href='/category/{$cat_slug}/' class='herald-cat-{$cat_slug}'>{$cat_name}</a>";
    }

    if ($cat_html) {
        echo '<span class="meta-category">';
        echo implode('<span> &bull; </span>', $cat_html);
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