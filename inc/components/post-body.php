<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="entry-content herald-entry-content">

        <?php

        $parsed_content = parse_shortcodes($post['content']);

        echo $parsed_content;

        ?>
    </div>
</div>