<?php slot('title') ?>
    DoubleBow - <?php echo $article['title'] ?>
<?php end_slot(); ?>
<?php slot('dynamic_js') ?>
    <?php for($i = 0,$n = count($article['dynjs']); $i < $n; $i++): ?>
        <?php echo '<script type="text/javascript" src="' . $article['dynjs'][$i] .'.js"></script> ' ?>
    <?php endfor; ?>
<?php end_slot(); ?>
<?php slot('dynamic_css') ?>
    <?php for($i = 0, $n = count($article['dyncss']); $i < $n; $i++): ?>
        <?php echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $article['dyncss'][$i] . '.css"/>' ?>
    <?php endfor; ?>
<?php end_slot(); ?>

<?php include_partial('header', array('menu_link' => 'articles')) ?>
<div class="container_10 content">
    <div class="grid_7">
        <div class="left_column article">
            <div class="main_content">
                <div class="float_box">
                    <a class="top" href="#top">top</a>
                    <?php if($comments) : ?>
                        <a class="comments" href="#comments">comments</a>
                    <?php endif; ?>
                    <div class="soc">
                        <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
                        <fb:like href="" layout="button_count" show_faces="false" width="90" font="verdana"></fb:like>
                    </div>
                    <div class="soc">
                        <a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a>
                        <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
                    </div>
                </div>
                <div class="date"><?php echo date_format(date_create($article['date']), 'j-m-Y') ?></div>
                <div>
                    <h2><?php echo $article['title'] ?></h2>
                    <a class="comments_title" href="#comments"><?php echo $article['comment_count'] ?></a>
                    <div class="clear"></div>
                </div>
                <div class="article_categories">
                    <?php foreach($article['categories'] as $catSlug => $catName): ?>
                        <?php echo link_to($catName, '/articles/' . $catSlug) . '&nbsp;' ?>
                    <?php endforeach; ?>
                </div>
                <div class="article_text">
                    <p>
                        <?php echo html_entity_decode($article['abstract']) ?>
                    </p>
                    <?php echo html_entity_decode($article['content']) ?>
                    <div class="article_author">
                        written by &nbsp;<span><?php echo $article['author'] ?></span>
                    </div>
                </div>
            </div>
            <div class="comment_submit" id="post">
                <h2>Leave a comment</h2>

                <form action="#post" method="post">
                    <div class="user_data">
                        <div class="data">
                            <?php echo $form['author']->renderLabel() ?>
                            <?php echo $form['author']->renderError() ?>
                        </div>
                        <div class="data">
                            <?php echo $form['author']->render() ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="user_data">
                        <div class="data">
                            <?php echo $form['mail']->renderLabel() ?>
                            <?php echo $form['mail']->renderError() ?>
                        </div>
                        <div class="data">
                            <?php echo $form['mail']->render() ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="user_data">
                        <div class="data">
                            <?php echo $form['web']->renderLabel() ?>
                            <?php echo $form['web']->renderError() ?>
                        </div>
                        <div class="data">
                            <?php echo $form['web']->render() ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <div class="message_elem">
                            <?php echo $form['content']->renderLabel() ?>
                            <?php echo $form['content']->renderError() ?>
                        </div>
                        <div class="message_elem">
                            <?php echo $form['content']->render() ?>
                            <div class="info">
                                You can use HTML tags: &lt;br&gt; &lt;code&gt;  &lt;/code&gt;  &lt;b&gt;  &lt;/b&gt;
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="user_data">
                        <div class="data">
                            <?php echo $form['check']->renderLabel('How much is '.$add1.' + '.$add2.'? *') ?>
                            <?php echo $form['check']->renderError() ?>
                        </div>
                        <div class="data">
                            <?php echo $form['check']->render() ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php if ($form->isCSRFProtected()) : ?>
                        <?php echo $form['_csrf_token']->render(); ?>
                    <?php endif; ?>
                    <input type="submit" value="Submit" />
                </form>
                
            </div>
            <div class="comment_content">
                <?php if($comments) : ?>
                    <h2 id="comments">Comments</h2>
                    <?php foreach($comments as $id => $comment): ?>
                        <div class="comment">
                            <div class="pic"></div>
                            <div class="user_data">
                                <div class="date">
                                    <?php
                                        $day = date_format(date_create($comment['date']), 'j');

                                        switch($day) {
                                            case '1': echo '1st'; break;
                                            case '2': echo '2nd'; break;
                                            default: echo $day . 'th';
                                        }
                                        echo date_format(date_create($comment['date']), ' F Y, G:i')
                                    ?>
    <!--                                15th October 2011, 11:23-->
                                </div>
                                <div class="author">
                                    <?php echo $comment['author'] ?>
                                    <div><?php echo $comment['mail'] ?></div>
                                    <div><?php echo $comment['web'] ?></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="message">
                                <?php
                                echo str_replace(
                                    array('&lt;br&gt;', '&lt;br/&gt;', '&lt;code&gt;', '&lt;/code&gt;', '&lt;b&gt;', '&lt;/b&gt;'),
                                    array('<br/>', '<br/>', '<pre><code>', '</code></pre>', '<b>', '</b>'),
                                    $comment['content']
                                )
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include_component('articles', 'sidebar') ?>
    <div class="clear"></div>
</div>
<?php include_component('articles', 'footer') ?>
