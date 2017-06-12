<?php slot('title') ?>
    DoubleBow - Categories
<?php end_slot(); ?>

<?php include_partial('articles/header', array('menu_link' => 'categories')) ?>
<div class="container_10 content">
    <div class="grid_7">
        <div class="left_column">
            <h2 class="page">Categories</h2>
            <div class="float_box">
                <a class="top" href="#top">top</a>
            </div>
            <?php foreach($categories as $slug => $category): ?>
                <div class="main_content">
                    <div>
                        <h2><?php echo link_to($category['name'], '/articles/'.$slug) ?></h2>
                    </div>
                    <div class="category_article_cnt">
                        <?php
                            echo $category['article_cnt'] . ' article' .
                            (intval($category['article_cnt']) > 1 ? 's' : '' );
                        ?>
                    </div>
                    <div class="article_text">
                        <p class="abstract">
                            <?php echo $category['description'] ?>
                            <?php echo link_to('articles', '/articles/'.$slug, array('class' => 'more')) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include_component('articles', 'sidebar') ?>
    <div class="clear"></div>
</div>
<?php include_component('articles', 'footer') ?>