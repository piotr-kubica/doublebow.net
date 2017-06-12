<div class="footer_content">
    <div>
        <div class="parent_block">
            <?php if($articles) : ?>
                <h2>Articles</h2>
                <?php
                    $articles = $sf_data->getRaw('articles');
                ?>
                <?php for($i = 0; $i < $cols; $i++): ?>
                    <div class="block">
                        <?php for($k = $i * $colCnt, $cnt = $i * $colCnt + $colCnt; $k < $cnt; $k++): ?>
                            <?php
                                $slug = $ak[$k];
                                $article = $articles[$slug];
                                $carrk = array_keys($article['categories']);
                                // $carrk[0] is first categoty slug
                                echo link_to($article['title'], '/articles/' . $carrk[0] . '/' . $slug);
                            ?>
                            <br />
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
                <div class="clear"></div>
            <?php endif; ?>
        </div>
        <div class="block">
            <?php if($categories) : ?>
                <h2>Categories</h2>
                <div>
                <?php foreach($categories as $slug => $category): ?>
                    <?php echo link_to($category['name'], '/articles/'.$slug) ?>
                    <br />
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>

    <div class="copyright">
        <h1>DoubleBow</h1> &copy; 2011. All rights reserved.
    </div>
</div>