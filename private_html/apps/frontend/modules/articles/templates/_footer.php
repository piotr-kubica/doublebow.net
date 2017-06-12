<div class="container_10 footer">
    <div class="grid_10">
        <div class="footer_content">
            <div>
                <div class="block">
                    <?php if($articles): ?>
                    <h2>Articles</h2>
                    <div>
                        <?php foreach($articles as $slug => $v): ?>
                            <?php echo link_to($v['title'], '/articles/' . $v['category_slug'] . '/' . $slug) ?>
                            <br />
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="block">
                    <?php if($categories): ?>
                    <h2>Categories</h2>
                    <div>
                        <?php foreach($categories as $slug => $name): ?>
                            <?php echo link_to($name, '/articles/' . $slug) ?>
                            <br />
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="block wide profile">
                    <h2>About</h2>
                    <div>
                        <div class="pic"></div>
                        Hi!
                        <br />
                        My name is Peter.
                        I'm a webdeveloper.
                        <br /><br />
                        Contact me by mail:
                        <br />
                        double2bow@gmail.com
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="copyright">
                <h1>DoubleBow</h1> &copy; 2011. All rights reserved.
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>