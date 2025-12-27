<!-- $authorEmail, $authorName, $authorSlug, $authorLine -->
<div id="author" class="herald-vertical-padding">
    <div class="herald-mod-wrap">
        <div class="herald-mod-head ">
            <div class="herald-mod-title">
                <h4 class="h6 herald-mod-h herald-color">About the author</h4>
            </div>
            <div class="herald-mod-actions"><a href="/author/<?= $authorSlug ?>/">View All Posts</a>
            </div>
        </div>
    </div>
    <div class="herald-author row">
        <div class="herald-author-data col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <img alt="" src="<?= get_gravatar_url($authorEmail, 140) ?>" class="avatar avatar-140 photo" height="140"
                width="140" loading="lazy" decoding="async" />
        </div>
        <div class="herald-data-content col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <h4 class="author-title"><?= $authorName ?></h4>
            <p><?= $authorLine ?></p>
        </div>
    </div>
</div>