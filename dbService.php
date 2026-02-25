<?php

// View

// -- Base view with all post data joined
"CREATE VIEW view_posts AS
SELECT 
p.id,
p.title,
p.slug,
p.is_dynamic,
p.deadbase_id,
p.pubDate,
p.content,
p.sticky,
p.post_type,
p.views,

i.file_path,
i.is_image_new,

a.author_slug,
a.author_display_name,
a.author_email,
a.author_quote,

COALESCE(cmt.comment_count, 0) AS comments,

COALESCE(
    (SELECT JSON_ARRAYAGG(
        JSON_OBJECT('slug', CONCAT('/category/', c.cat_slug), 'name', c.cat_name)
    )
    FROM post_categories pc
    JOIN categories c ON c.cat_id = pc.category_id
    WHERE pc.post_id = p.id
    ), JSON_ARRAY()
) AS categories,

COALESCE(
    (SELECT JSON_ARRAYAGG(
        JSON_OBJECT('slug', CONCAT('/genre/', g.genre_slug), 'name', g.genre_name)
    )
    FROM post_genres pg
    JOIN genres g ON g.genre_id = pg.genre_id
    WHERE pg.post_id = p.id
    ), JSON_ARRAY()
) AS genres,

COALESCE(
    (SELECT JSON_ARRAYAGG(
        JSON_OBJECT('slug', CONCAT('/tag/', t.tag_slug), 'name', t.tag_name)
    )
    FROM posts_tag pt
    JOIN tags t ON t.tag_id = pt.tag_id
    WHERE pt.post_id = p.id
    ), JSON_ARRAY()
) AS tags

FROM posts p
LEFT JOIN images i ON i.id = p.thumbnail
LEFT JOIN authors a ON a.author_id = p.author
LEFT JOIN (
SELECT post_id, COUNT(*) AS comment_count
FROM comments
WHERE com_status = 1
GROUP BY post_id
) cmt ON cmt.post_id = p.id
WHERE p.post_type = 'post'";
function author_posts($author, $limit, $offset, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT 
        id, title, slug, comments, categories, pubDate, file_path,is_image_new, author_slug, author_display_name, author_email, author_quote
        FROM view_posts
        WHERE author_slug = :author
        ORDER BY pubDate DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':author', $author, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalStmt = $pdo->prepare("
        SELECT COUNT(*) FROM view_posts WHERE author_slug = :author
    ");
    $totalStmt->execute([':author' => $author]);
    $total = $totalStmt->fetchColumn();

    return ['posts' => $posts, 'total' => $total];
}

function category($cat, $limit, $offset, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT 
            id, title, slug, comments, categories, pubDate, file_path,is_image_new, author_slug,author_display_name 
        FROM view_posts p
        JOIN post_categories pc ON pc.post_id = p.id
        JOIN categories c ON c.cat_id = pc.category_id
        WHERE c.cat_slug = :cat
        ORDER BY p.pubDate DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':cat', $cat, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalStmt = $pdo->prepare("
        SELECT COUNT(DISTINCT p.id)
        FROM view_posts p
        JOIN post_categories pc ON pc.post_id = p.id
        JOIN categories c ON c.cat_id = pc.category_id
        WHERE c.cat_slug = :cat
    ");
    $totalStmt->execute([':cat' => $cat]);
    $total = $totalStmt->fetchColumn();

    return ['posts' => $posts, 'total' => $total];
}

function posts($excludeIds, $limit, $offset, $pdo)
{
    $excludeIds = array_values(array_filter(array_map('intval', (array) $excludeIds)));
    $excludeSql = '';
    $params = [];

    if (!empty($excludeIds)) {
        $placeholders = [];
        foreach ($excludeIds as $i => $id) {
            $key = ':ex' . $i;
            $placeholders[] = $key;
            $params[$key] = $id;
        }
        $excludeSql = 'AND id NOT IN (' . implode(',', $placeholders) . ')';
    }

    $stmt = $pdo->prepare("
        SELECT 
            id, title, slug, comments, categories, pubDate, file_path, is_image_new, author_slug,author_display_name 
        FROM view_posts
        WHERE 1=1 $excludeSql
        ORDER BY pubDate DESC 
        LIMIT :limit OFFSET :offset
    ");

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_INT);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function single($slug, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM view_posts p WHERE p.slug = :slug");
    $stmt->execute([':slug' => $slug]);
    $res = $stmt->fetch();

    $update = $pdo->prepare("UPDATE posts SET views = views + 1 WHERE slug = :slug");
    $update->execute([':slug' => $slug]);

    return $res;
}
function search($s, $limit, $offset, $pdo)
{
    $s = trim($s);
    $escaped = preg_quote($s);
    $searchTerm = '\\b' . $escaped . '\\b';

    $terms = explode(" ", $s);
    $termsClause = '%' . implode('%', $terms) . '%';
    $likePattern = "%$s%";

    // Get distinct IDs with priority, then join to view_posts
    $stmt = $pdo->prepare("
        SELECT 
            vp.id, vp.title, vp.slug, vp.comments, vp.categories, 
            vp.pubDate, vp.file_path, vp.is_image_new, vp.author_slug, vp.author_display_name
        FROM (
            SELECT id, 1 as priority, pubDate
            FROM view_posts
            WHERE title RLIKE :search1
            
            UNION
            
            SELECT id, 2 as priority, pubDate
            FROM view_posts
            WHERE title LIKE :like
            AND id NOT IN (SELECT id FROM view_posts WHERE title RLIKE :search2)
            
            UNION
            
            SELECT id, 3 as priority, pubDate
            FROM view_posts
            WHERE title LIKE :terms
            AND id NOT IN (
                SELECT id FROM view_posts 
                WHERE title RLIKE :search3 OR title LIKE :like2
            )
            
            UNION
            
            SELECT id, 4 as priority, pubDate
            FROM view_posts
            WHERE content RLIKE :search4
            AND id NOT IN (
                SELECT id FROM view_posts 
                WHERE title RLIKE :search5 OR title LIKE :like3 OR title LIKE :terms2
            )
            
            ORDER BY priority ASC, pubDate DESC
            LIMIT :limit OFFSET :offset
        ) as ranked
        JOIN view_posts vp ON vp.id = ranked.id
        ORDER BY ranked.priority ASC, ranked.pubDate DESC
    ");

    $stmt->bindParam(':search1', $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(':search2', $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(':search3', $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(':search4', $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(':search5', $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(':like', $likePattern, PDO::PARAM_STR);
    $stmt->bindParam(':like2', $likePattern, PDO::PARAM_STR);
    $stmt->bindParam(':like3', $likePattern, PDO::PARAM_STR);
    $stmt->bindParam(':terms', $termsClause, PDO::PARAM_STR);
    $stmt->bindParam(':terms2', $termsClause, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total count
    $countStmt = $pdo->prepare("
        SELECT COUNT(DISTINCT id) as total FROM (
            SELECT id FROM view_posts WHERE title RLIKE :search1
            UNION
            SELECT id FROM view_posts WHERE title LIKE :like
            UNION
            SELECT id FROM view_posts WHERE title LIKE :terms
            UNION
            SELECT id FROM view_posts WHERE content RLIKE :search2
        ) as search_results
    ");

    $countStmt->bindParam(':search1', $searchTerm, PDO::PARAM_STR);
    $countStmt->bindParam(':search2', $searchTerm, PDO::PARAM_STR);
    $countStmt->bindParam(':like', $likePattern, PDO::PARAM_STR);
    $countStmt->bindParam(':terms', $termsClause, PDO::PARAM_STR);
    $countStmt->execute();
    $total = $countStmt->fetchColumn();

    return [
        'posts' => $posts,
        'total' => $total
    ];
}

function totalPosts($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM posts");
    return $stmt->fetchColumn();
}

function genre($genre, $limit, $offset, $pdo)
{
    $stmt = $pdo->prepare(
        "SELECT
            id, title, slug, comments, genres as categories, pubDate, file_path, is_image_new, author_slug,author_display_name 
        FROM view_posts p
        JOIN post_genres pg ON pg.post_id = p.id
        JOIN genres g ON g.genre_id = pg.genre_id
        WHERE g.genre_slug = :genre
        ORDER BY p.pubDate DESC LIMIT :limit OFFSET :offset
    "
    );

    $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll();

    $totalStmt = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM posts p
        JOIN post_genres pg ON pg.post_id = p.id
        JOIN genres g ON g.genre_id = pg.genre_id
        WHERE g.genre_slug = :genre
    ");
    $totalStmt->execute([':genre' => $genre]);
    $total = $totalStmt->fetchColumn();

    return ['posts' => $posts, 'total' => $total];
}

function featured($pdo)
{
    $stmt = $pdo->prepare("SELECT 
        id, title, slug, comments, categories, pubDate, file_path, is_image_new, author_slug,author_display_name 
    FROM view_posts p WHERE p.sticky = 1");
    $stmt->execute();
    $featuredPosts = $stmt->fetchAll();
    return $featuredPosts;
}