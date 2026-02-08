<?php
function author_posts($author, $limit, $offset, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT p.id
        FROM posts p
        JOIN authors a ON a.author_id = p.author
        WHERE p.post_type = 'post' AND a.author_slug = :author
        ORDER BY p.pubDate DESC LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':author', $author, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $posts = posts_by_ids($pdo, $ids, true);

    $totalStmt = $pdo->prepare("
        SELECT COUNT(*) AS total FROM posts
        JOIN authors ON authors.author_id = posts.author
        WHERE posts.post_type = 'post' AND authors.author_slug = :author
    ");
    $totalStmt->execute([':author' => $author]);
    $total = $totalStmt->fetchColumn();

    return ['posts' => $posts, 'total' => $total];
}

function tag($tag, $limit, $offset, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT DISTINCT p.id
        FROM posts p
        JOIN posts_tag pt ON pt.post_id = p.id
        JOIN tags t ON t.tag_id = pt.tag_id
        WHERE t.tag_slug = :tag
        ORDER BY p.pubDate DESC LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':tag', $tag, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $res = posts_by_ids($pdo, $ids, true);

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

function single($slug, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT p.*,
            images.file_path,
            a.author_slug,a.author_email,a.author_display_name,a.author_quote,
            JSON_ARRAYAGG(
                JSON_OBJECT(
                    'slug', CONCAT('/category/',c.cat_slug),
                    'name',c.cat_name
                )
            ) as categories
        FROM posts p
        LEFT JOIN post_categories pc ON pc.post_id = p.id
        LEFT JOIN categories c ON c.cat_id = pc.category_id
        LEFT JOIN images ON images.id = p.thumbnail
        LEFT JOIN authors a ON a.author_id = p.author 
        WHERE p.slug = :slug
        GROUP BY p.id;
    ");
    $stmt->execute([':slug' => $slug]);
    $res = $stmt->fetch();

    $update = $pdo->prepare("UPDATE posts SET views = views + 1 WHERE slug = :slug");
    $update->execute([':slug' => $slug]);

    return $res;
}
function category($cat, $limit, $offset, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT DISTINCT p.id
        FROM posts p
        JOIN post_categories pc ON pc.post_id = p.id
        JOIN categories c ON c.cat_id = pc.category_id
        WHERE p.post_type = 'post' AND c.cat_slug = :cat
        ORDER BY p.pubDate DESC LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':cat', $cat, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $posts = posts_by_ids($pdo, $ids, true);

    $totalStmt = $pdo->prepare("
    SELECT COUNT(*) as total
    FROM posts p
    JOIN post_categories pc ON pc.post_id = p.id
    JOIN categories c ON c.cat_id = pc.category_id
    WHERE p.post_type = 'post' AND c.cat_slug = :cat
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

    $stmt1 = $pdo->prepare("
        SELECT p.id
        FROM posts p
        WHERE p.post_type = 'post' AND p.title RLIKE :search
        ORDER BY p.pubDate DESC
    ");
    $stmt1->execute([':search' => $searchTerm]);
    $p1ids = array_map('intval', $stmt1->fetchAll(PDO::FETCH_COLUMN));
    $p1idsex = $p1ids ? "AND p.id NOT IN (" . implode(',', $p1ids) . ")" : "";

    $sql2 = "
        SELECT p.id
        FROM posts p
        WHERE p.post_type = 'post' AND p.title LIKE :like $p1idsex
        ORDER BY p.pubDate DESC
    ";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([':like' => "%$s%"]);
    $p2ids = array_map('intval', $stmt2->fetchAll(PDO::FETCH_COLUMN));
    $all_ids = array_merge($p1ids, $p2ids);
    $p2idsex = $all_ids ? "AND p.id NOT IN (" . implode(',', $all_ids) . ")" : "";

    $terms = explode(" ", $s);
    $termsClause = '%' . implode('%', $terms) . '%';
    $sql3 = "
        SELECT p.id
        FROM posts p
        WHERE p.post_type = 'post' AND p.title LIKE :terms $p2idsex
        ORDER BY p.pubDate DESC
    ";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([':terms' => $termsClause]);
    $p3ids = array_map('intval', $stmt3->fetchAll(PDO::FETCH_COLUMN));
    $all_ids = array_merge($p1ids, $p2ids, $p3ids);
    $p3idsex = $all_ids ? "AND p.id NOT IN (" . implode(',', $all_ids) . ")" : "";

    $sql4 = "
        SELECT p.id
        FROM posts p
        WHERE p.post_type = 'post' AND p.content RLIKE :search $p3idsex
        ORDER BY p.pubDate DESC
    ";
    $stmt4 = $pdo->prepare($sql4);
    $stmt4->execute([':search' => $searchTerm]);
    $p4ids = array_map('intval', $stmt4->fetchAll(PDO::FETCH_COLUMN));

    $all_ids = array_values(array_unique(array_merge($p1ids, $p2ids, $p3ids, $p4ids)));
    $paged_ids = array_slice($all_ids, $offset, $limit);
    $posts = posts_by_ids($pdo, $paged_ids, true);

    return [
        'posts' => $posts,
        'total' => count($all_ids)
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
        "SELECT p.id 
        FROM posts p
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
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $posts = posts_by_ids($pdo, $ids, true);

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
        $excludeSql = 'AND p.id NOT IN (' . implode(',', $placeholders) . ')';
    }

    $stmt = $pdo->prepare(
        "SELECT p.id
        FROM posts p
        WHERE p.post_type = 'post' $excludeSql
        ORDER BY p.pubDate DESC LIMIT :limit OFFSET :offset
    "
    );
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_INT);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return posts_by_ids($pdo, $ids, true);
}

function posts_by_ids($pdo, $ids, $orderByIds = false)
{
    $ids = array_values(array_unique(array_filter(array_map('intval', (array) $ids))));
    if (empty($ids)) {
        return [];
    }

    $placeholders = [];
    $params = [];
    foreach ($ids as $i => $id) {
        $key = ':id' . $i;
        $placeholders[] = $key;
        $params[$key] = $id;
    }

    $orderSql = 'p.pubDate DESC';
    if ($orderByIds) {
        $orderSql = 'FIELD(p.id, ' . implode(',', $ids) . ')';
    }

    $stmt = $pdo->prepare(
        "SELECT 
            p.id,
            p.title,
            p.pubDate,
            p.slug,
            i.file_path,

            COALESCE(cmt.comment_count, 0) AS comments,

            a.author_slug,
            a.author_display_name,
            a.author_email,
            a.author_quote,

            COALESCE(cat.categories, JSON_ARRAY()) AS categories

        FROM posts p

        JOIN images i 
            ON i.id = p.thumbnail

        LEFT JOIN authors a 
            ON a.author_id = p.author

        LEFT JOIN (
            SELECT post_id, COUNT(*) AS comment_count
            FROM comments
            WHERE com_status = 1
            GROUP BY post_id
        ) cmt ON cmt.post_id = p.id

        LEFT JOIN (
            SELECT 
                pc.post_id,
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'slug', CONCAT('/category/' ,c.cat_slug),
                        'name', c.cat_name
                    )
                ) AS categories
            FROM post_categories pc
            JOIN categories c 
                ON c.cat_id = pc.category_id
            GROUP BY pc.post_id
        ) cat ON cat.post_id = p.id

        WHERE p.post_type = 'post' AND p.id IN (" . implode(',', $placeholders) . ")
        ORDER BY $orderSql
    "
    );

    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function featured($pdo)
{
    $stmt = $pdo->prepare("SELECT p.id FROM posts p WHERE p.sticky = 1");
    $stmt->execute();
    $featuredIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $featuredPosts = posts_by_ids($pdo, $featuredIds);
    return $featuredPosts;
}   