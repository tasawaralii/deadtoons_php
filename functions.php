<?php

function get_anime_links($animeId, $season, $type, $honly)
{

    $api = $_ENV['API_URL'] . "/show-links";

    if ($animeId != '') {
        if ($type == "tv") {
            if ($honly == 1) {
                $url = $api . '?animeId=' . $animeId . '&season=' . $season . '&type=' . $type . '&hinOnly=1&key=deadtoonszylith';
            } else {
                $url = $api . '?animeId=' . $animeId . '&season=' . $season . '&type=' . $type . '&hinOnly=0&key=deadtoonszylith';
            }
        } else {
            $url = $api . "?animeId=$animeId&type=$type=&key=deadtoonszylith";
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
        print_r($e);
        return 'error';
    }
}

function makeDynamicPostBody($deadbase_id)
{
    $api = $_ENV['API_URL'];

    $post_code = fetchContent($api . "/post-code?deadbase_slug=$deadbase_id");
    return $post_code;
}

function make_image_url($file_id, $size = 'mid', $is_new = false)
{

    if (!$is_new) {
        if ($size == 'mid') {
            $ex = pathinfo($file_id);
            return $_ENV['OLD_IMAGE_DOMAIN'] . "/content/" . $ex['dirname'] . '/' . $ex['filename'] . '-640x360.' . $ex['extension'];
        }
        return $_ENV['OLD_IMAGE_DOMAIN'] . "/content/" . $file_id;
    }

    $res = "w780";
    if ($size == "high") $res = "w1280";
    if ($size == "low") $res = "w300";

    return $_ENV['IMAGE_DOMAIN'] . "/" . $res . $file_id;
}

function get_gravatar_url($email, $size = 64)
{
    $email = strtolower(trim($email));
    $hash = md5($email);
    return "https://www.gravatar.com/avatar/$hash?s=$size";
}

function pagination($total, $pgno, $limit)
{
    if ($total <= 0 || $limit <= 0) {
        return '';
    }

    $pages = (int) ceil($total / $limit);
    if ($pages <= 1) {
        return '';
    }

    // Helper to build URLs while keeping existing GET params
    $buildUrl = function ($page) {
        $params = $_GET;
        $params['page'] = $page;
        return '?' . http_build_query($params);
    };

    $output = '<nav class="herald-pagination">';

    // Previous
    if ($pgno > 1) {
        $output .= '<a class="prev page-numbers" href="' . $buildUrl($pgno - 1) . '">Previous</a>';
    }

    // First page + dots
    if ($pgno > 3) {
        $output .= '<a class="page-numbers" href="' . $buildUrl(1) . '">1</a>';
        if ($pgno > 4) {
            $output .= '<span class="page-numbers dots">&hellip;</span>';
        }
    }

    // Pages around current
    for ($i = max(1, $pgno - 2); $i <= min($pages, $pgno + 2); $i++) {
        if ($i == $pgno) {
            $output .= '<span aria-current="page" class="page-numbers current">' . $i . '</span>';
        } else {
            $output .= '<a class="page-numbers" href="' . $buildUrl($i) . '">' . $i . '</a>';
        }
    }

    // Last page + dots
    if ($pgno < $pages - 2) {
        if ($pgno < $pages - 3) {
            $output .= '<span class="page-numbers dots">&hellip;</span>';
        }
        $output .= '<a class="page-numbers" href="' . $buildUrl($pages) . '">' . $pages . '</a>';
    }

    // Next
    if ($pgno < $pages) {
        $output .= '<a class="next page-numbers" href="' . $buildUrl($pgno + 1) . '">Next</a>';
    }

    $output .= '</nav>';

    return $output;
}

function article($a, $sticky = false)
{
    $categories = json_decode($a['categories'], true);
    $category_links = array_map(fn($c) => '<a href="' . $c['slug'] . '">' . $c['name'] . '</a>', $categories);

    echo '<article class="herald-lay-b post-' . $a['id'] . ' post type-post status-publish format-standard has-post-thumbnail' . (($sticky) ? " sticky" : '') . ' hentry">';
    echo '<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
			<div class="herald-post-thumbnail herald-format-icon-middle">
				<a href="/' . $a['slug'] . '"
				 title="' . $a['title'] . '">
					<img src="' . make_image_url($a['file_path'], 'mid', $a['is_image_new']) .
        '" class="attachment-herald-lay-b1 size-herald-lay-b1 wp-post-image" alt="" 
					</a>
		</div>
		</div>
	<div class="col-lg-8 col-md-8 col-sm-8">
		<div class="entry-header">
			<span class="meta-category">';
    echo implode('<span> &bull; </span>', $category_links);
    echo '<h2 class="entry-title h3">
				<a href="/' . $a['slug'] . '">' . $a['title'] . '</a></h2>
				<div class="entry-meta">
					<div class="meta-item herald-date">
						<span class="updated">' . time_elapsed_string($a['pubDate']) . '</span>
					</div>
					<div class="meta-item herald-comments">
						<a href="/' . $a['slug'] . '#comments">' . $a['comments'] . ' Comments</a></div>
                        <div class="meta-item herald-author">
                        <span class="vcard author">
                        <span class="fn">
                        <a href="/' . 'author/' . $a['author_slug'] . '">' . $a['author_display_name'] . '</a>
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
            . '<a href="/category/' . $slug . '">' . $name . '</a>'
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
            . '<a href="/category/' . $cslug . '">' . $cname . '</a>'
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
            . '<a href="/genre/' . $gslug . '">' . $gname . '</a>'
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
    if ($cached !== null)
        return $cached;

    try {
        $fresh = get_popular_posts($pdo, $limit);
        cache_set_today($cacheKey, $fresh);
        return $fresh;
    } catch (Throwable $e) {
        $raw = cache_read_raw($cacheKey);
        if ($raw && isset($raw['value']))
            return $raw['value'];
        return [];
    }
}
