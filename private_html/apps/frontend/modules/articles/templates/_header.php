<div class="container_10 header">
    <div class="grid_7">
        <?php echo link_to('ouble<span>bow</span>', '/articles/latest', array('class' => 'double_bow')) ?>
        
        <div class="double_bow_sub">web developers blog</div>
        <div class="top_menu">
            <?php echo link_to('articles', '/articles', array('class' => (($menu_link == 'articles') ? 'active' : ''))) ?>
            <?php echo link_to('categories', '/categories', array('class' => (($menu_link == 'categories') ? 'active' : ''))) ?>
            <?php echo link_to('portfolio', '/portfolio', array('class' => (($menu_link == 'portfolio') ? 'active' : ''))) ?>
            <?php echo link_to('about', '/about', array('class' => (($menu_link == 'about') ? 'active' : ''))) ?>
            <div class="clear"></div>
        </div>
    </div>
    <div class="grid_3">
        <!-- // FIXME change form action in production env -->
        <form name="searchform" class="search_form" action="/articles/search/s" method="post">
            <label for="search_input">Search</label>
            <div>
                <input type="text" id="pkeywords" name="pkeywords" size="26" />
                <input type="submit" value="" />
                <div class="clear"></div>
            </div>
        </form>
        <div class="social_buttons">
            <a class="facebook" href="http://www.facebook.com/pages/doublebownet/192687707442635"></a>
            <a class="twitter" href="http://twitter.com/#!/doublebow_"></a>
            <a class="rss" href="http://feeds.feedburner.com/doublebow"></a>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>