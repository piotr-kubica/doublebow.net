<div class="main_content">
    <div class="date_box">
        <div class="day">
        <?php
            $day = date_format(date_create($article['date']), 'j');

            switch($day) {
                case '1': echo '1st'; break;
                case '2': echo '2nd'; break;
                default: echo $day . 'th';
            }
        ?>
        </div>
        <div class="month"><?php echo date_format(date_create($article['date']), 'F') ?></div>
        <div class="month"><?php echo date_format(date_create($article['date']), 'Y') ?></div>
    </div>
    <div>
        <?php
            $categories = $sf_data->getRaw('categories');
            $carrk = array_keys($categories);
            $firstCategotySlug = $carrk[0];
        ?>
        <h2><?php echo link_to($article['title'], '/articles/' . $firstCategotySlug . '/' . $slug) ?></h2>
        <?php echo link_to($article['comment_count'], 
                           '/articles/' . $firstCategotySlug . '/' . $slug . '#comments',
                           array('class' => 'comments_title'))
        ?>
        <div class="clear"></div>
    </div>
    <div class="article_categories">
        <?php foreach($categories as $catSlug => $catName): ?>
            <?php echo link_to($catName, '/articles/' . $catSlug) . '&nbsp;' ?>
        <?php endforeach; ?>
    </div>
    <div class="article_text">
        <p class="abstract">
            <?php echo html_entity_decode($article['abstract']); ?>
            <?php echo link_to('read more', '/articles/' . $firstCategotySlug . '/' . $slug, array('class' => 'more')) ?>
        </p>
    </div>
</div>