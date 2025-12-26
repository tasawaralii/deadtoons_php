<?php
// require('db.php');

// $xml = simplexml_load_file('deadtoonsindia_animeandcartoonvideosinhindidownload_WordPress_2024.xml') or die("Error: Cannot create object");
echo "<pre>";

// // $o = json_encode($xml);
// // $xml = json_decode($o,true);
// // print_r($xml);


//         $img = $pdo->prepare("
// INSERT INTO `images`(`id`, `title`, `pubDate`, `creator`, `file_path`, `post_id`, `width`, `height`, `size`) 
// VALUES (:id,:title,:date,1,:path,:post,:width,:height,:size)");


// $cmnt = $pdo->prepare("
//     INSERT INTO comments (com_id, post_id, parent_id, com_author, com_email, com_author_url, com_date, com_content, com_status) 
//     VALUES (:id, :post_id, :par_id, :author, :email, :url, :date, :content, :status)
// ");

// $postt = $pdo->prepare("
//     INSERT IGNORE INTO posts (id, title, slug, pubDate, author, content, sticky, views, post_type) 
//     VALUES (:id, :title, :slug, :date, :author, :content, :sticky, :view)
// ");

foreach ($xml->channel->item as $post) {
    $type = (string)$post->children('wp', true)->post_type;
    if (in_array($type, ['custom_css', 'wp_navigation', 'nav_menu_item'])) {
        continue;
    } elseif (in_array($type, ['post'])) {
        $p_id = (int)$post->children('wp', true)->post_id;


        
    foreach ($post->children('wp', true)->postmeta as $s) {
        if ($s->children('wp', true)->meta_key == '_thumbnail_id') {
            echo $slug = $s->children('wp', true)->meta_value;

            $pdo->query("UPDATE posts set thumbnail = $slug where id = $p_id");

        }
        echo "<br>";
    }

    // foreach ($post->category as $category) {
    //     $domain = (string) $category['domain'];
    //     $nicename = (string) $category['nicename'];

    //         if($domain == "post_tag") {
    //           echo  $tag_id = $pdo->query("SELECT tag_id FROM tags where tag_slug = '$nicename'")->fetch(PDO::FETCH_COLUMN);
    //           if(!$tag_id) {
    //             echo $nicename;
    //             exit;
    //           }
    //         echo $sql = "INSERT IGNORE INTO `posts_tag`(`post_id`, `tag_id`, `tag_type`) VALUES ($p_id,$tag_id,1)";
    //           $pdo->query($sql);
    //         }

    //     $name = (string) $category;
    // }

        // foreach($post->children('wp', true)->comment as $c) {
        //     $id = $c->children('wp', true)->comment_id;
        //     $par_id = $c->children('wp', true)->comment_parent;
        //     $author = $c->children('wp', true)->comment_author;
        //     $email = $c->children('wp', true)->comment_author_email;
        //     $url = $c->children('wp', true)->comment_author_url;
        //     $date = $c->children('wp', true)->comment_date;
        //     echo $status = $c->children('wp', true)->comment_approved;
            
        //     $content = $c->children('wp', true)->comment_content;

        //     $cmnt->execute([
        //         ':id' => $id,
        //         ':post_id' => $p_id,
        //         ':par_id' => $par_id,
        //         ':author' => $author,
        //         ':email' => $email,
        //         ':url' => $url,
        //         ':date' => $date,
        //         ':content' => $content,
        //         ':status' => $status
        //     ]);

            // echo "<br>";
        //     continue;
        }




     elseif ($type == 'attachment') {
    
    // $title = (string)$post->title;
    // $id = (int)$post->children('wp', true)->post_id;
    // $date = (string)$post->children('wp', true)->post_date;
    // $post_id = $post->children('wp', true)->post_parent;

    // foreach ($post->children('wp', true)->postmeta as $s) {
    //     if ($s->children('wp', true)->meta_key == '_wp_attached_file') {
    //         $slug = $s->children('wp', true)->meta_value;
    //     }
    //     if ((string)$s->meta_key === "_wp_attachment_metadata") {
    //         $meta_value = (string)$s->meta_value;

            // Unserialize the meta value
            // $meta_data = unserialize($meta_value);

            // Extract main image data
    //         $width = $meta_data['width'];
    //         $height = $meta_data['height'];
    //         $size = $meta_data['filesize'];
    //     }
    }

//     $img->execute([
//         ':id' => $id,
//         ':title' => $title,
//         ':path' => $slug,
//         ':date' => $date,
//         ':post' => $post_id,
//         ':width' => $width,
//         ':height' => $height,
//         ':size' => $size,
//     ]);
// }
//     echo "<br>";
}
// echo "Authors inserted successfully!";

?>