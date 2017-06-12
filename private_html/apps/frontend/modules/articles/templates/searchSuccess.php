<?php slot('title') ?>
    DoubleBow - Search Results
<?php end_slot(); ?>

<?php include_partial('header', array('menu_link' => 'articles')) ?>
<div class="container_10 content">
    <div class="grid_7">
        <div class="left_column articles">
            <?php if($search_results): ?>
                <h2 class="page">Search results for: <i><?php echo $keywords ?></i></h2>
                <?php foreach($sf_data->getRaw('search_results') as $slug => $article): ?>
                    <?php
                    include_partial('searchresult',
                        array('article' => $article,
                              'slug' => $slug,
                              'result_index' => $result_index++,
                              'categories' => $article['categories']));
                    ?>
                <?php endforeach; ?>
                <?php
                    include_partial('pageBar',
                        array('pager' => $pager,
                              'current_page' => $current_page,
                              'pageUrl' => '/articles/latest/'));
                ?>
            <?php else: ?>
                <h2 class="page">No results found</h2>
            <?php endif; ?>
        </div>
    </div>
        <?php include_component('articles', 'sidebar') ?>
    <div class="clear"></div>
</div>
<?php include_component('articles', 'footer') ?>