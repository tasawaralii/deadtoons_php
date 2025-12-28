<style>
    .telegram b {
        color: #a32c2c;
    }
</style>
<div class="herald-sidebar col-lg-3 col-md-3 herald-sidebar-right">
    <div id="custom_html-4" class="widget_text widget widget_custom_html">
        <h4 class="widget-title h6"><span>Follow On Social Media</span></h4>
        <div class="textwidget custom-html-widget">
            <div class="telegram">
                <p>Follow Our <b>Fan Channel</b> To get Notified About Latest <b>Updates</b>.</p>
                <a href="https://t.me/addlist/slvdlq5NovRmYzI0" target="_blank" rel="noopener">
                    <img src="/public/images/join-telegram.png" alt="Deadtoons Telegram" />
                </a>
                <p>Join Our <b>Fan Channel</b> Managed By Our <b>Fans</b>.</p>
            </div>
        </div>
    </div>
    <div id="text-2" class="widget widget_text">
        <h4 class="widget-title h6"><span>Popular Posts</span></h4>
        <div class="textwidget">
            <?php
            $posts = get_popular_posts_cached($pdo, 15);
            foreach ($posts as $p)
                echo "<p><a href='/" . htmlspecialchars($p['slug']) . "/' >" . htmlspecialchars($p['title']) . "</a></p>";
            ?>
        </div>
    </div>
</div>