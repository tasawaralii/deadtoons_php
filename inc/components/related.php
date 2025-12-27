<?php

$sql = "SELECT 
            posts.id,posts.title,posts.pubDate,posts.slug, 
            images.file_path
        FROM 
            posts
        JOIN 
            images ON images.id = posts.thumbnail
        WHERE 
            posts.post_type = 'post'
        ORDER BY RAND() LIMIT 3";

$related = $pdo->query($sql)->fetchAll();

?>




<div id="related" class="herald-related-wrapper">

    <div class="herald-mod-wrap">
        <div class="herald-mod-head ">
            <div class="herald-mod-title">
                <h4 class="h6 herald-mod-h herald-color">You may also like</h4>
            </div>
        </div>
    </div>
    <div class="herald-related row row-eq-height">
        <?php
        foreach ($related as $r) {
            $ex = pathinfo($r['file_path']);
            $imgUrl = IMAGE_DOMAIN . "/" . $ex['dirname'] . '/' . $ex['filename'] . '-640x360.' . $ex['extension'];

            echo '

        <article class="herald-lay-f herald-lay-f1">
<div class="herald-ovrld">		
        <div class="herald-post-thumbnail">
        <a href="/' . $r['slug'] . "/" . '" title="' . $r['title'] . '"><img width="300" height="200" src=' . $imgUrl . ' class="attachment-herald-lay-f1 size-herald-lay-f1 wp-post-image" alt="" /></a>
    </div>

<div class="entry-header herald-clear-blur">
    <h2 class="entry-title h6"><a href="/' . $r['slug'] . '/">' . $r['title'] . '</a></h2>
        </div>
</div>


</article>
    
    ';
        }
        ?>
    </div>
</div>