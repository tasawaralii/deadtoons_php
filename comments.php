<?php

$author = "";
$email = "";
$url = "";

if (isset($_COOKIE['dead_comment'])) {
    $c = json_decode($_COOKIE['dead_comment'], true);
    $author = $c['author'];
    $email = $c['email'];
    $url = $c['url'];
}

// Fetch the comments from the database
$res = $pdo->query("SELECT * FROM `comments` WHERE post_id = $com_post_id and com_status = 1 ORDER BY com_date ASC")->fetchAll();

// Start HTML output
echo '<div id="comments" class="herald-comments">';
echo '<div class="herald-mod-wrap"><div class="herald-mod-head "><div class="herald-mod-title"><h4 class="h6 herald-mod-h herald-color">' . count($res) . ' Comments</h4></div></div></div>';
echo '<div class="herald-gray-area"><span class="herald-fake-button herald-comment-form-open">Click here to post a comment</span></div>';
echo '<div id="respond" class="comment-respond">';
echo '<h3 id="reply-title" class="comment-reply-title"><small><a rel="nofollow" id="cancel-comment-reply-link" href="#" style="display:none;">Cancel reply</a></small></h3>';
echo '<form action="/comments-post.php" method="post" id="commentform" class="comment-form">';
echo '<p class="comment-form-comment"><label for="comment">Comment</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
echo '<p class="comment-form-author"><label for="author">Name <span class="required">*</span></label><input id="author" name="author" type="text" value="' . $author . '" size="30" maxlength="245" autocomplete="name" required></p>';
echo '<p class="comment-form-email"><label for="email">Email <span class="required">*</span></label><input id="email" name="email" type="email" value="' . $email . '" size="30" maxlength="100" autocomplete="email" required></p>';
echo '<p class="comment-form-url"><label for="url">Website</label><input id="url" name="url" type="text" value="' . $url . '" size="30" maxlength="200" autocomplete="url" /></p>';
echo '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" /><label for="wp-comment-cookies-consent">Save my name, email, and website in this browser for the next time I comment.</label></p>';
echo '<p class="form-submit"><input name="submit" type="submit" id="submit" class="submit" value="Post comment" /><input type="hidden" name="comment_post_ID" value="' . $post['id'] . '" id="comment_post_ID" /><input type="hidden" name="comment_parent" id="comment_parent" value="0" /></p>';
echo '<input type="hidden" name="post_slug" value="' . $post['slug'] . '" />';
echo '</form>';
echo '</div><!-- #respond -->';

echo '<ul class="comment-list">';

$comment_tree = [];
foreach ($res as $comment) {
    $comment_tree[$comment['parent_id']][] = $comment;
}

function render_comments($comments, $comment_tree)
{
    foreach ($comments as $comment) {
        echo '<li id="comment-' . $comment['com_id'] . '" class="comment">';
        echo '<article id="div-comment-' . $comment['com_id'] . '" class="comment-body">';
        echo '<footer class="comment-meta">';
        echo '<div class="comment-author vcard">';
        echo '<img alt="" src="https://secure.gravatar.com/avatar/' . md5($comment['com_email']) . '?s=60&d=wavatar&r=g" class="avatar avatar-60 photo" height="60" width="60" loading="lazy" decoding="async"/>';
        echo '<b class="fn">' . htmlspecialchars($comment['com_author']) . '</b> <span class="says">says:</span>';
        echo '</div><!-- .comment-author -->';
        echo '<div class="comment-metadata">';
        echo '<a href="#"><time datetime="' . $comment['com_date'] . '">' . date("F j, Y \a\\t g:i a", strtotime($comment['com_date'])) . '</time></a>';
        echo '</div><!-- .comment-metadata -->';
        echo '</footer><!-- .comment-meta -->';
        echo '<div class="comment-content">';
        echo '<p>' . nl2br(htmlspecialchars($comment['com_content'])) . '</p>';
        echo '</div><!-- .comment-content -->';
        echo '<div class="reply"><a rel="nofollow" class="comment-reply-link" href="#" data-commentid="' . $comment['com_id'] . '" data-postid="1867" data-belowelement="div-comment-' . $comment['com_id'] . '" data-respondelement="respond" data-replyto="Reply to ' . htmlspecialchars($comment['com_author']) . '">Reply</a></div>';
        echo '</article><!-- .comment-body -->';

        if (isset($comment_tree[$comment['com_id']])) {
            echo '<ul class="children">';
            render_comments($comment_tree[$comment['com_id']], $comment_tree);
            echo '</ul>';
        }

        echo '</li>';
    }
}

if (isset($comment_tree[0])) {
    render_comments($comment_tree[0], $comment_tree);
}

echo '</ul><!-- .comment-list -->';
echo '</div><!-- #comments -->';

?>

<!-- JavaScript to handle the reply functionality -->
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('.comment-reply-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const commentId = this.getAttribute('data-commentid');
                const respondElement = document.getElementById('respond');
                const parentIdInput = document.getElementById('comment_parent');

                // Move the comment form to below the clicked comment
                const commentElement = document.getElementById('comment-' + commentId);
                commentElement.appendChild(respondElement);

                // Set the parent_id input to the comment ID
                parentIdInput.value = commentId;

                // Show the cancel reply link
                document.getElementById('cancel-comment-reply-link').style.display = 'block';
            });
        });

        // Cancel reply functionality
        document.getElementById('cancel-comment-reply-link').addEventListener('click', function (e) {
            e.preventDefault();
            const respondElement = document.getElementById('respond');
            const parentIdInput = document.getElementById('comment_parent');

            // Move the comment form back to its original position
            document.querySelector('.herald-gray-area').insertAdjacentElement('afterend', respondElement);

            // Reset the parent_id input to 0
            parentIdInput.value = '0';

            // Hide the cancel reply link
            this.style.display = 'none';
        });
    });
</script>