<?php

function get_anime_links($animeId, $season, $type, $honly)
{

    $api = "http://deadbase-old.local";

    if ($animeId != '') {
        if ($type == "tv") {
            if ($honly == 1) {
                $url = $api . '/fulltoons/show-links.php?animeId=' . $animeId . '&season=' . $season . '&type=' . $type . '&hinOnly=1&key=deadtoonszylith';
            } else {
                $url = $api . '/fulltoons/show-links.php?animeId=' . $animeId . '&season=' . $season . '&type=' . $type . '&hinOnly=0&key=deadtoonszylith';
            }
        } else {
            $url = $api . "/fulltoons/show-links.php?animeId=$animeId&type=$type=&key=deadtoonszylith";
        }

        $content = fetchContent($url);

        return $content;
    } else {
        return '<center style="color:red">Links Under Maintenance</center>';
    }
}

function fetchContent($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        // echo "cURL Error: " . curl_error($ch);
        return false;
    }

    curl_close($ch);
    return $response;
}


function parse_shortcodes($content)
{
    // Regular expression to match shortcodes
    $pattern = '/\[deadbase animeid="(\d+)"(?: season="(\w+)")? type="(\w+)"(?: honly="(\d)")?\]/';

    return preg_replace_callback($pattern, function ($matches) {
        $animeId = $matches[1];
        $season = $matches[2];
        $type = $matches[3];
        $honly = isset($matches[4]) ? $matches[4] : 0;

        // Get the content using the custom function
        return get_anime_links($animeId, $season, $type, $honly);
    }, $content);
}


function resize_image($inputFile, $outputFile, $ex)
{
    try {
        // Create a new Imagick object and read the input file
        $image = new Imagick($inputFile);

        // Convert the image to the specified format
        $image->setImageFormat($ex);

        // Get original dimensions
        $originalWidth = $image->getImageWidth();
        $originalHeight = $image->getImageHeight();

        // Define the target dimensions
        $targetWidth = 640;
        $targetHeight = 360;

        // Calculate the new dimensions while maintaining the aspect ratio
        if ($originalWidth / $targetWidth > $originalHeight / $targetHeight) {
            $newWidth = $targetWidth;
            $newHeight = intval($originalHeight * ($targetWidth / $originalWidth));
        } else {
            $newHeight = $targetHeight;
            $newWidth = intval($originalWidth * ($targetHeight / $originalHeight));
        }

        // Resize the image
        $image->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1);

        // Save the output file
        $image->writeImage($outputFile);

        // Clear the Imagick object
        $image->clear();
        $image->destroy();

        return 'success';
    } catch (Exception $e) {
        return 'error';
    }
}


function tag($tag, $limit, $offset, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT 
            posts.*,
            images.file_path,
            authors.author_slug,
            authors.author_email,
            authors.author_display_name,
            authors.author_quote,
            GROUP_CONCAT(DISTINCT categories.cat_slug) AS cat_slugs,
            GROUP_CONCAT(DISTINCT categories.cat_name) AS cat_names,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND comments.com_status = 1) AS comments,
            GROUP_CONCAT(DISTINCT tags.tag_slug) AS tag_slugs,
            GROUP_CONCAT(DISTINCT tags.tag_name) AS tag_names
        FROM 
            posts
        JOIN 
            images ON images.id = posts.thumbnail
        LEFT JOIN 
            posts_tag ON posts_tag.post_id = posts.id
        LEFT JOIN 
            categories ON categories.cat_id = posts_tag.tag_id AND posts_tag.tag_type = 2
        LEFT JOIN 
            tags ON tags.tag_id = posts_tag.tag_id AND posts_tag.tag_type = 1
        LEFT JOIN
            authors ON authors.author_id = posts.author 
        WHERE 
            tags.tag_slug = :tag
        GROUP BY 
            posts.id, images.file_path, authors.author_slug, authors.author_email, 
            authors.author_display_name, authors.author_quote
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':tag', $tag, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalStmt = $pdo->prepare("
        SELECT COUNT(*) AS total FROM posts 
        JOIN posts_tag ON posts_tag.post_id = posts.id 
        JOIN tags ON tags.tag_id = posts_tag.tag_id 
        WHERE tags.tag_slug = :tag
    ");
    $totalStmt->execute([':tag' => $tag]);
    $total = $totalStmt->fetchColumn();

    return ['posts' => $res, 'total' => $total];
}


function author_posts($author, $limit, $offset, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT 
            posts.id, posts.title, posts.pubDate, posts.slug, 
            images.file_path,
            GROUP_CONCAT(categories.cat_slug) AS cat_slugs,
            GROUP_CONCAT(categories.cat_name) AS cat_names,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND comments.com_status = 1) AS comments,
            authors.author_slug,authors.author_display_name,authors.author_email,authors.author_quote
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
            posts.post_type = 'post' AND authors.author_slug = :author
        GROUP BY 
            posts.id, images.file_path
        ORDER BY 
            posts.pubDate DESC LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':author', $author, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalStmt = $pdo->prepare("
        SELECT COUNT(*) AS total FROM posts
        JOIN authors ON authors.author_id = posts.author
        WHERE posts.post_type = 'post' AND authors.author_slug = :author
    ");
    $totalStmt->execute([':author' => $author]);
    $total = $totalStmt->fetchColumn();

    return ['posts' => $posts, 'total' => $total];
}


function single($slug, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT posts.*,images.file_path,
            authors.author_slug,authors.author_email,authors.author_display_name,authors.author_quote,
            GROUP_CONCAT(categories.cat_slug) AS cat_slugs,
            GROUP_CONCAT(categories.cat_name) AS cat_names,
            GROUP_CONCAT(tags.tag_slug) AS tag_slugs,
            GROUP_CONCAT(tags.tag_name) AS tag_names 
        FROM posts
        LEFT JOIN images ON images.id = posts.thumbnail
        LEFT JOIN posts_tag ON posts_tag.post_id = posts.id
        LEFT JOIN categories ON categories.cat_id = posts_tag.tag_id AND posts_tag.tag_type = 2
        LEFT JOIN tags ON tags.tag_id = posts_tag.tag_id AND posts_tag.tag_type = 1
        LEFT JOIN authors ON authors.author_id = posts.author 
        WHERE slug = :slug
        GROUP BY posts.id
    ");
    $stmt->execute([':slug' => $slug]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);

    $update = $pdo->prepare("UPDATE posts SET views = views + 1 WHERE slug = :slug");
    $update->execute([':slug' => $slug]);

    return $res;
}


function genre($cat, $limit, $offset, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT 
            posts.id, posts.title, posts.pubDate, posts.slug, images.file_path,
            GROUP_CONCAT(genres.genre_slug) AS cat_slugs,
            GROUP_CONCAT(genres.genre_name) AS cat_names,
            MAX(CASE WHEN genres.genre_slug = :cat THEN genres.genre_name ELSE NULL END) AS cat_name,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND comments.com_status = 1) AS comments,
            authors.author_slug, authors.author_display_name
        FROM posts
        JOIN images ON images.id = posts.thumbnail
        LEFT JOIN posts_tag ON posts_tag.post_id = posts.id AND posts_tag.tag_type = 3
        LEFT JOIN genres ON genres.genre_id = posts_tag.tag_id
        LEFT JOIN authors ON authors.author_id = posts.author
        WHERE posts.post_type = 'post'
        GROUP BY posts.id, images.file_path
        HAVING FIND_IN_SET(:cat, cat_slugs) > 0
        ORDER BY posts.pubDate DESC LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':cat', $cat, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalStmt = $pdo->prepare("
        SELECT COUNT(*) AS total FROM posts 
        JOIN posts_tag ON posts_tag.post_id = posts.id 
        JOIN genres ON genres.genre_id = posts_tag.tag_id 
        WHERE genres.genre_slug = :cat
    ");
    $totalStmt->execute([':cat' => $cat]);
    $total = $totalStmt->fetchColumn();

    return ['posts' => $posts, 'total' => $total];
}


function category($cat, $limit, $offset, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT 
            posts.id, posts.title, posts.pubDate, posts.slug, images.file_path,
            GROUP_CONCAT(categories.cat_slug) AS cat_slugs,
            GROUP_CONCAT(categories.cat_name) AS cat_names,
            MAX(CASE WHEN categories.cat_slug = :cat THEN categories.cat_name ELSE NULL END) AS cat_name,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND comments.com_status = 1) AS comments,
            authors.author_slug, authors.author_display_name
        FROM posts
        JOIN images ON images.id = posts.thumbnail
        LEFT JOIN posts_tag ON posts_tag.post_id = posts.id AND posts_tag.tag_type = 2
        LEFT JOIN categories ON categories.cat_id = posts_tag.tag_id
        LEFT JOIN authors ON authors.author_id = posts.author
        WHERE posts.post_type = 'post'
        GROUP BY posts.id, images.file_path
        HAVING FIND_IN_SET(:cat, cat_slugs) > 0
        ORDER BY posts.pubDate DESC LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':cat', $cat, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalStmt = $pdo->prepare("
        SELECT COUNT(*) AS total FROM posts 
        JOIN posts_tag ON posts_tag.post_id = posts.id 
        JOIN categories ON categories.cat_id = posts_tag.tag_id 
        WHERE categories.cat_slug = :cat
    ");
    $totalStmt->execute([':cat' => $cat]);
    $total = $totalStmt->fetchColumn();

    return ['posts' => $posts, 'total' => $total];
}


function search($s, $limit, $offset, $pdo)
{
    $s = trim($s);
    $escaped = preg_quote($s);
    $searchTerm = '\\b' . $escaped . '\\b';

    // First Query: RLIKE exact word match
    $stmt1 = $pdo->prepare("
        SELECT 
            posts.id, posts.title, posts.pubDate, posts.slug, 
            images.file_path,
            GROUP_CONCAT(categories.cat_slug) AS cat_slugs,
            GROUP_CONCAT(categories.cat_name) AS cat_names,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND comments.com_status = 1) AS comments,
            authors.author_slug, authors.author_display_name
        FROM posts
        JOIN images ON images.id = posts.thumbnail
        LEFT JOIN posts_tag ON posts_tag.post_id = posts.id AND posts_tag.tag_type = 2
        LEFT JOIN categories ON categories.cat_id = posts_tag.tag_id
        LEFT JOIN authors ON authors.author_id = posts.author
        WHERE posts.post_type = 'post' AND posts.title RLIKE :search
        GROUP BY posts.id, images.file_path
        ORDER BY posts.pubDate DESC
    ");
    $stmt1->execute([':search' => $searchTerm]);
    $p1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    $p1ids = array_column($p1, 'id');
    $p1idsex = $p1ids ? "AND posts.id NOT IN (" . implode(',', $p1ids) . ")" : "";

    // 2 Second Query: LIKE match (title)
    $sql2 = "
        SELECT 
            posts.id, posts.title, posts.pubDate, posts.slug, 
            images.file_path,
            GROUP_CONCAT(categories.cat_slug) AS cat_slugs,
            GROUP_CONCAT(categories.cat_name) AS cat_names,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND comments.com_status = 1) AS comments,
            authors.author_slug, authors.author_display_name
        FROM posts
        JOIN images ON images.id = posts.thumbnail
        LEFT JOIN posts_tag ON posts_tag.post_id = posts.id AND posts_tag.tag_type = 2
        LEFT JOIN categories ON categories.cat_id = posts_tag.tag_id
        LEFT JOIN authors ON authors.author_id = posts.author
        WHERE posts.post_type = 'post' AND posts.title LIKE :like $p1idsex
        GROUP BY posts.id, images.file_path
        ORDER BY posts.pubDate DESC
    ";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([':like' => "%$s%"]);
    $p2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $p2ids = array_column($p2, 'id');
    $all_ids = array_merge($p1ids, $p2ids);
    $p2idsex = $all_ids ? "AND posts.id NOT IN (" . implode(',', $all_ids) . ")" : "";

    // 3 ️Third Query: LIKE with spaced terms
    $terms = explode(" ", $s);
    $termsClause = '%' . implode('%', $terms) . '%';
    $sql3 = "
        SELECT 
            posts.id, posts.title, posts.pubDate, posts.slug, 
            images.file_path,
            GROUP_CONCAT(categories.cat_slug) AS cat_slugs,
            GROUP_CONCAT(categories.cat_name) AS cat_names,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND comments.com_status = 1) AS comments,
            authors.author_slug, authors.author_display_name
        FROM posts
        JOIN images ON images.id = posts.thumbnail
        LEFT JOIN posts_tag ON posts_tag.post_id = posts.id AND posts_tag.tag_type = 2
        LEFT JOIN categories ON categories.cat_id = posts_tag.tag_id
        LEFT JOIN authors ON authors.author_id = posts.author
        WHERE posts.post_type = 'post' AND posts.title LIKE :terms $p2idsex
        GROUP BY posts.id, images.file_path
        ORDER BY posts.pubDate DESC
    ";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([':terms' => $termsClause]);
    $p3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    $p3ids = array_column($p3, 'id');
    $all_ids = array_merge($p1ids, $p2ids, $p3ids);
    $p3idsex = $all_ids ? "AND posts.id NOT IN (" . implode(',', $all_ids) . ")" : "";

    // 4 Fourth Query: RLIKE match in content
    $sql4 = "
        SELECT 
            posts.id, posts.title, posts.pubDate, posts.slug, 
            images.file_path,
            GROUP_CONCAT(categories.cat_slug) AS cat_slugs,
            GROUP_CONCAT(categories.cat_name) AS cat_names,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND comments.com_status = 1) AS comments,
            authors.author_slug, authors.author_display_name
        FROM posts
        JOIN images ON images.id = posts.thumbnail
        LEFT JOIN posts_tag ON posts_tag.post_id = posts.id AND posts_tag.tag_type = 2
        LEFT JOIN categories ON categories.cat_id = posts_tag.tag_id
        LEFT JOIN authors ON authors.author_id = posts.author
        WHERE posts.post_type = 'post' AND posts.content RLIKE :search $p3idsex
        GROUP BY posts.id, images.file_path
        ORDER BY posts.pubDate DESC
    ";
    $stmt4 = $pdo->prepare($sql4);
    $stmt4->execute([':search' => $searchTerm]);
    $p4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

    // Merge all
    $all = array_merge($p1, $p2, $p3, $p4);

    return [
        'posts' => array_slice($all, $offset, $limit),
        'total' => count($all)
    ];
}


function totalPosts($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM posts");
    return $stmt->fetchColumn();
}


function posts($excludeFeaturedClause, $limit, $offset, $pdo)
{
    // To safely append exclude clause, ensure it starts with a valid AND or is empty
    $allowedClause = '';
    if ($excludeFeaturedClause && preg_match('/^(AND|OR)\s/i', $excludeFeaturedClause)) {
        $allowedClause = $excludeFeaturedClause;
    }

    $stmt = $pdo->prepare("
        SELECT 
            posts.id, posts.title, posts.pubDate, posts.slug, 
            images.file_path,
            GROUP_CONCAT(categories.cat_slug) AS cat_slugs,
            GROUP_CONCAT(categories.cat_name) AS cat_names,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND comments.com_status = 1) AS comments,
            authors.author_slug, authors.author_display_name
        FROM posts
        JOIN images ON images.id = posts.thumbnail
        LEFT JOIN posts_tag ON posts_tag.post_id = posts.id AND posts_tag.tag_type = 2
        LEFT JOIN categories ON categories.cat_id = posts_tag.tag_id
        LEFT JOIN authors ON authors.author_id = posts.author
        WHERE posts.post_type = 'post' $allowedClause
        GROUP BY posts.id, images.file_path
        ORDER BY posts.pubDate DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function featured($pdo)
{
    $featuredQuery = $pdo->query("SELECT 
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
    posts.sticky = 1 AND posts.post_type = 'post'
GROUP BY 
    posts.id, 
    images.file_path
ORDER BY 
    posts.pubDate DESC;");

    $featuredPosts = $featuredQuery->fetchAll(PDO::FETCH_ASSOC);
    return $featuredPosts;
}

function get_gravatar_url($email, $size = 64)
{
    $email = strtolower(trim($email));
    $hash = md5($email);
    return "https://www.gravatar.com/avatar/$hash?s=$size";
}

function pagination($total, $pgno, $limit, $domain, $s, $cat)
{
    if (!$total == 0) {
        $output = '<nav class="herald-pagination">';

        if ($pgno > 1) {
            $output .= '<a class="prev page-numbers" href="' . $domain . $cat . '/page/' . ($pgno - 1) . '/' . $s . '">Previous</a>';
        }
        $pages = $total % $limit == 0 ? $total / $limit : ($total / $limit) + 1;
        $pages = intval($pages);
        // if pages exists after loop's lower limit
        if ($pages > 1) {
            if (($pgno - 3) > 0) {
                $output = $output . '<a href="' . $domain . $cat . '/page/1/' . $s . '" class="page-numbers' . (($pgno == 1) ? "current" : '') . '">1</a>';
            }
            if (($pgno - 3) > 1) {
                $output = $output . '<span class="page-numbers dots">&hellip;</span>';
            }

            // Loop for provides links for 2 pages before and after current page
            for ($i = ($pgno - 2); $i <= ($pgno + 2); $i++) {
                if ($i < 1)
                    continue;
                if ($i > $pages)
                    break;
                if ($pgno == $i)
                    $output = $output . '<span aria-current="page" class="page-numbers current">' . $i . '</span>';
                else
                    $output = $output . '<a class="page-numbers" href="' . $domain . $cat . '/page/' . $i . '/' . $s . '">' . $i . '</a>';
            }

            // if pages exists after loop's upper limit
            if (($pages - ($pgno + 2)) > 1) {
                $output = $output . '<span class="page-numbers dots">&hellip;</span>';
            }
            if (($pages - ($pgno + 2)) > 0) {
                if ($pgno == $pages)
                    $output = $output . '<span aria-current="page" class="page-numbers current">' . $pages . '</span>';
                else
                    $output = $output . '<a class="page-numbers" href="' . $domain . $cat . '/page/' . $pages . '/' . $s . '">' . $pages . '</a>';
            }
        }
        if ($pgno != $pages)
            $output .= '<a class="next page-numbers" href="' . $domain . $cat . '/page/' . ($pgno + 1) . '/' . $s . '">Next</a>';
        $output .= '</nav>';
        return $output;
    }
    return;
}

function article($a, $cat, $sticky = false)
{
    echo '<article class="herald-lay-b post-' . $a['id'] . ' post type-post status-publish format-standard has-post-thumbnail' . (($sticky) ? " sticky" : '') . ' hentry">';
    echo '<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
			<div class="herald-post-thumbnail herald-format-icon-middle">
				<a href="/' . $a['slug'] . '/"
				 title="' . $a['title'] . '">
					<img width="640" height="360" 
					src="' . IMAGE_DOMAIN . '/';

    $ex = pathinfo($a['file_path']);
    echo $ex['dirname'] . '/' . $ex['filename'] . '-640x360.' . $ex['extension'] . '" 
					class="attachment-herald-lay-b1 size-herald-lay-b1 wp-post-image" alt="" 
					</a>
		</div>
		</div>
	<div class="col-lg-8 col-md-8 col-sm-8">
		<div class="entry-header">
			<span class="meta-category">';
    $cat_slugs = $a['cat_slugs'] ? explode(',', $a['cat_slugs']) : [];
    $cat_names = $a['cat_names'] ? explode(',', $a['cat_names']) : [];
    $cat_html = [];
    foreach ($cat_slugs as $index => $cat_slug) {
        $cat_name = $cat_names[$index];
        $cat_html[] = "<a href=/'$cat/{$cat_slug}/' class='herald-cat-{$cat_slug}'>{$cat_name}</a>";
    }
    echo implode('<span> &bull; </span>', $cat_html);
    echo '<h2 class="entry-title h3">
				<a href="/' . $a['slug'] . '/">' . $a['title'] . '</a></h2>
				<div class="entry-meta">
					<div class="meta-item herald-date">
						<span class="updated">' . time_elapsed_string($a['pubDate']) . '</span>
					</div>
					<div class="meta-item herald-comments">
						<a href="/' . $a['slug'] . '#comments">' . $a['comments'] . ' Comments</a></div>
                        <div class="meta-item herald-author">
                        <span class="vcard author">
                        <span class="fn">
                        <a href="/' . 'author/' . $a['author_slug'] . '/">' . $a['author_display_name'] . '</a>
                        </span>
                        </span>
                        </div>
                        </div>
					</div>
			</div>
		</div>
	</article>	
	';
}
function time_elapsed_string($datetime, $full = false)
{
    $timezone = new DateTimeZone('Asia/Karachi');
    $now = new DateTime('now', $timezone);
    $ago = new DateTime($datetime, $timezone);
    $diff = $now->diff($ago);

    // Compute weeks without modifying the DateInterval object
    $weeks = floor($diff->d / 7);
    $days = $diff->d - ($weeks * 7);

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );

    // Build the time string
    $time_values = array(
        'y' => $diff->y,
        'm' => $diff->m,
        'w' => $weeks,
        'd' => $days,
        'h' => $diff->h,
        'i' => $diff->i,
        's' => $diff->s,
    );

    foreach ($string as $k => &$v) {
        if ($time_values[$k]) {
            $v = $time_values[$k] . ' ' . $v . ($time_values[$k] > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function cache_dir()
{
    return __DIR__ . DIRECTORY_SEPARATOR . 'cache';
}

function cache_read_raw($key)
{
    $file = cache_dir() . DIRECTORY_SEPARATOR . $key . '.json';
    if (!is_file($file))
        return null;
    $json = @file_get_contents($file);
    if ($json === false)
        return null;
    $data = json_decode($json, true);
    return is_array($data) ? $data : null;
}

function cache_get_today($key)
{
    $raw = cache_read_raw($key);
    if (!$raw)
        return null;
    $today = date('Y-m-d');
    if (isset($raw['date']) && $raw['date'] === $today && isset($raw['value'])) {
        return $raw['value'];
    }
    return null;
}

function cache_set_today($key, $value)
{
    $dir = cache_dir();
    if (!is_dir($dir))
        @mkdir($dir, 0775, true);
    $file = $dir . DIRECTORY_SEPARATOR . $key . '.json';
    $payload = [
        'date' => date('Y-m-d'),
        'value' => $value
    ];
    @file_put_contents($file, json_encode($payload), LOCK_EX);
}

function get_menu_data($pdo)
{
    // Fetch specific categories in a defined order
    $desired = [
        ['name' => 'completed', 'slug' => 'completed'],
        ['name' => 'movie', 'slug' => 'movie'],
        ['name' => 'marvel', 'slug' => 'marvel']
    ];

    $cstmt = $pdo->prepare("SELECT cat_name, cat_slug FROM categories ORDER BY cat_name ASC");
    $cstmt->execute();
    $categories = $cstmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all genres alphabetically
    $gstmt = $pdo->prepare("SELECT genre_name, genre_slug FROM genres ORDER BY genre_name ASC");
    $gstmt->execute();
    $genres = $gstmt->fetchAll(PDO::FETCH_ASSOC);

    return ['desired' => $desired, 'categories' => $categories, 'genres' => $genres];
}

function get_menu_data_cached($pdo)
{
    $cached = cache_get_today('menu_data');
    if ($cached !== null)
        return $cached;

    try {
        $fresh = get_menu_data($pdo);
        cache_set_today('menu_data', $fresh);
        return $fresh;
    } catch (Throwable $e) {
        $raw = cache_read_raw('menu_data');
        if ($raw && isset($raw['value']))
            return $raw['value'];
        return ['categories' => [], 'genres' => []];
    }
}

function build_menu_html($menuData, $ulId, $ulClass)
{
    $desired = isset($menuData['desired']) ? $menuData['desired'] : [];
    $categories = isset($menuData['categories']) ? $menuData['categories'] : [];
    $genres = isset($menuData['genres']) ? $menuData['genres'] : [];

    $html = '<ul id="' . htmlspecialchars($ulId) . '" class="' . htmlspecialchars($ulClass) . '">';

    // Home
    $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home">'
        . '<a href="/" aria-current="page">Home</a>'
        . '</li>';

    // Top-level categories
    foreach ($desired as $d) {
        $slug = htmlspecialchars($d['slug']);
        $name = htmlspecialchars($d['name']);
        $html .= '<li class="menu-item menu-item-type-taxonomy menu-item-object-category">'
            . '<a href="/category/' . $slug . '/">' . $name . '</a>'
            . '</li>';
    }


    // Category dropdown
    $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children">'
        . '<a href="#">Categories</a>'
        . '<ul class="sub-menu">';
    foreach ($categories as $c) {
        $cslug = htmlspecialchars($c['cat_slug']);
        $cname = htmlspecialchars($c['cat_name']);
        $html .= '<li class="menu-item menu-item-type-taxonomy menu-item-object-genre">'
            . '<a href="/genres/' . $cslug . '/">' . $cname . '</a>'
            . '</li>';
    }
    $html .= '</ul></li>';

    // Genre dropdown
    $html .= '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children">'
        . '<a href="#">Genre</a>'
        . '<ul class="sub-menu">';
    foreach ($genres as $g) {
        $gslug = htmlspecialchars($g['genre_slug']);
        $gname = htmlspecialchars($g['genre_name']);
        $html .= '<li class="menu-item menu-item-type-taxonomy menu-item-object-genre">'
            . '<a href="/genres/' . $gslug . '/">' . $gname . '</a>'
            . '</li>';
    }
    $html .= '</ul></li>';

    $html .= '</ul>';
    return $html;
}

function get_popular_posts($pdo, $limit = 15)
{
    $stmt = $pdo->prepare("SELECT title, slug FROM posts WHERE title NOT LIKE '%Naruto%' ORDER BY views DESC LIMIT :limit");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_popular_posts_cached($pdo, $limit = 15)
{
    $cacheKey = 'popular_posts_' . $limit;
    $cached = cache_get_today($cacheKey);
    if ($cached !== null) return $cached;

    try {
        $fresh = get_popular_posts($pdo, $limit);
        cache_set_today($cacheKey, $fresh);
        return $fresh;
    } catch (Throwable $e) {
        $raw = cache_read_raw($cacheKey);
        if ($raw && isset($raw['value'])) return $raw['value'];
        return [];
    }
}

?>