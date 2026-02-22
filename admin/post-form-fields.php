<?php
$postTitleValue = isset($post['title']) ? $post['title'] : '';
$postSlugValue = isset($post['slug']) ? $post['slug'] : '';
$postContentValue = isset($post['content']) ? $post['content'] : '';
$postIsDynamic = !empty($post['is_dynamic']);
$postDeadbaseId = isset($post['deadbase_id']) ? $post['deadbase_id'] : '';
$postSticky = !empty($post['sticky']);
$postTypeValue = isset($post['post_type']) && $post['post_type'] !== '' ? $post['post_type'] : 'post';
$postAuthorValue = isset($post['author']) ? $post['author'] : 1;
$selectedCategoryIds = isset($selectedCategoryIds) ? array_map('strval', $selectedCategoryIds) : [];
$selectedGenreIds = isset($selectedGenreIds) ? array_map('strval', $selectedGenreIds) : [];
?>

<div class="form-group">
    <label for="postTitle">Post Title</label>
    <input type="text" class="form-control" id="title" name="postTitle" placeholder="Enter title" value="<?php echo htmlspecialchars($postTitleValue); ?>" required>
</div>
<div class="form-group">
    <label for="postSlug">Post Slug</label>
    <input type="text" class="form-control" id="slug" name="postSlug" value="<?php echo htmlspecialchars($postSlugValue); ?>">
</div>
<div class="form-group">
    <label for="postContentVisual">Post Content</label>
    <div id="contentformated" contenteditable="true"><?php echo $postContentValue; ?></div>
    <textarea class="form-control" id="postContent" name="content" rows="10"><?php echo htmlspecialchars($postContentValue); ?></textarea>
</div>

<div class="form-group">
    <label for="is_dynamic">Is Dynamic</label>
    <input type="checkbox" id="is_dynamic" name="is_dynamic" <?php echo $postIsDynamic ? 'checked' : ''; ?>>
</div>
<div class="form-group">
    <label for="deadbase_id">Deadbase ID</label>
    <input type="text" class="form-control" id="deadbase_id" name="deadbase_id" value="<?php echo htmlspecialchars($postDeadbaseId); ?>">
</div>
<div class="form-group">
    <label for="sticky">Sticky</label>
    <input type="checkbox" id="sticky" name="sticky" <?php echo $postSticky ? 'checked' : ''; ?>>
</div>
<div class="form-group">
    <label for="post_type">Post Type</label>
    <select class="form-control" id="post_type" name="post_type">
        <option value="post" <?php echo $postTypeValue === 'post' ? 'selected' : ''; ?>>Post</option>
        <option value="page" <?php echo $postTypeValue === 'page' ? 'selected' : ''; ?>>Page</option>
    </select>
</div>
<div class="form-group">
    <label for="author">Author</label>
    <select class="form-control" id="author" name="author">
        <?php foreach ($authors as $a): ?>
            <option value="<?php echo $a['author_id']; ?>" <?php echo (string)$a['author_id'] === (string)$postAuthorValue ? 'selected' : ''; ?>><?php echo htmlspecialchars($a['author_display_name']); ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group">
    <label>Telegram</label>
    <input type="checkbox" name="totele" checked>
    <label>Episode</label>
    <input type="text" name="ep" value="Episode ">
    <label>Movie: </label>
    <input type="checkbox" name="movie">
    <label> Note: </label>
    <input type="text" name="note">
</div>

<div class="form-group">
    <label>Categories:</label>
    <div class="tag-grid">
        <?php foreach ($cats as $cat): ?>
            <?php $checked = in_array((string)$cat['cat_id'], $selectedCategoryIds, true) ? 'checked' : ''; ?>
            <label class="tag-pill">
                <input type="checkbox" name="categories[]" value="<?php echo $cat['cat_id']; ?>" <?php echo $checked; ?>>
                <span><?php echo htmlspecialchars($cat['cat_name']); ?></span>
            </label>
        <?php endforeach; ?>
    </div>
</div>
<div class="form-group">
    <label>Genres:</label>
    <div class="tag-grid">
        <?php foreach ($gens as $gen): ?>
            <?php $checked = in_array((string)$gen['genre_id'], $selectedGenreIds, true) ? 'checked' : ''; ?>
            <label class="tag-pill">
                <input type="checkbox" name="genres[]" value="<?php echo $gen['genre_id']; ?>" <?php echo $checked; ?>>
                <span><?php echo htmlspecialchars($gen['genre_name']); ?></span>
            </label>
        <?php endforeach; ?>
    </div>
</div>
