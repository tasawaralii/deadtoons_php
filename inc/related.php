<?php

$related = $pdo->query("SELECT 
posts.id,posts.title,posts.pubDate,posts.slug, 
images.file_path,
GROUP_CONCAT(categories.cat_slug) AS cat_slugs,
GROUP_CONCAT(categories.cat_name) AS cat_names,
(SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND comments.com_status = 1) AS comments,
authors.author_slug,authors.author_display_name
FROM 
posts
JOIN 
images ON images.id = posts.thumbnail
LEFT JOIN 
posts_tag ON posts_tag.post_id = posts.id AND posts_tag.tag_type = 2
LEFT JOIN 
categories ON categories.cat_id = posts_tag.tag_id
LEFT JOIN
authors ON authors.author_id = posts.author
WHERE 
posts.post_type = 'post'
GROUP BY 
posts.id, 
images.file_path
ORDER BY RAND() LIMIT 3")->fetchAll();

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
            foreach($related as $r) {
                $ex = pathinfo($r['file_path']);
  echo '

        <article class="herald-lay-f herald-lay-f1">
<div class="herald-ovrld">		
        <div class="herald-post-thumbnail">
        <a href="/'.$r['slug']."/".'" title="'.$r['title'].'"><img width="300" height="200" src="/content/'.$ex['dirname'].'/'.$ex['filename'].'-640x360.'.$ex['extension'].'" class="attachment-herald-lay-f1 size-herald-lay-f1 wp-post-image" alt="" /></a>
    </div>

<div class="entry-header herald-clear-blur">
    <h2 class="entry-title h6"><a href="/'.$r['slug'].'/">'.$r['title'].'</a></h2>
        </div>
</div>


</article>
    
    ';
            }
?>
</div>
</div>