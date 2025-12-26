<?php
require('db.php');
$sql = "SELECT title, slug, content, pubDate FROM posts ORDER BY pubDate DESC LIMIT 10";
$result = $pdo->query($sql)->fetchAll();

// RSS Feed header
header("Content-Type: application/rss+xml; charset=UTF-8");

// RSS Feed XML structure
echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo '<rss version="2.0">';
echo '<channel>';
echo '<title>Dead Toons India - Anime and Cartoon Videos in Hindi Download</title>';
echo '<link>https://deadtoons.pro</link>';
echo '<description>Latest anime and cartoon videos in Hindi</description>';
echo '<language>en-us</language>';

// Loop through posts and generate feed items
if ($result) {
    foreach($result as $row) {
        echo '<item>';
        echo '<title>' . htmlspecialchars($row["title"]) . '</title>';
        echo '<link>' . htmlspecialchars("https://deadtoons.pro/".$row["slug"]) . '</link>';
        echo '<description>' . htmlspecialchars(substr($row["content"], 0, 400)) . '</description>';
        echo '<pubDate>' . date(DATE_RSS, strtotime($row["pubDate"])) . '</pubDate>';
        echo '</item>';
    }
}

echo '</channel>';
echo '</rss>';
?>
