<?php slot('title') ?>
    DoubleBow - Latest Articles
<?php end_slot(); ?>

<?php include_partial('header', array('menu_link' => 'articles')) ?>
<div class="container_10 content">
    <div class="grid_7">
        <div class="left_column articles">
            <h2 class="page">Latest articles</h2>
            <?php foreach($articles as $slug => $article): ?>
                <?php
                include_partial('articlepreview',
                    array('article' => $article,
                          'slug' => $slug,
                          'categories' => $article['categories']));
                ?>
            <?php endforeach; ?>
            <?php
                include_partial('pageBar',
                    array('pager' => $pager,
                          'current_page' => $current_page,
                          'pageUrl' => '/articles/latest/'));
            ?>
        </div>
    </div>
        <?php include_component('articles', 'sidebar') ?>
    <div class="clear"></div>
</div>
<?php include_component('articles', 'footer') ?>