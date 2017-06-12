<div class="grid_3">
    <div class="side_menu">
        <h2>Articles</h2>
        <?php echo link_to('latest', '/articles/latest') ?>
        <?php echo link_to('most commented', '/articles/most-commented') ?>

        <?php if($categories): ?>
        <h2>Categories</h2>
            <?php foreach($categories as $slug => $name): ?>
                <?php echo link_to($name, '/articles/' . $slug) ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if($publYears): ?>
        <h2>Archive</h2>
            <?php foreach($publYears as $y => $ma): ?>
                <h3><?php echo $y ?></h3>
                <?php foreach($ma as $m => $cnt): ?>
                    <?php echo link_to($m, '/articles/' . $y . '/' . $m) ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <h2>Gr8 links</h2>
        <a href="http://www.alistapart.com/">A list apart</a>
        <a href="http://net.tutsplus.com/">Nettuts</a>
        <a href="http://www.flashperfection.com/">Flash Perfection</a>
        <a href="http://css-tricks.com/">css-tricks</a>
        <a href="http://www.dynamicdrive.com/">Dynamic Drive</a>
        <a href="http://www.kirupa.com/">kirupa.com</a>
        <a href="http://www.1stwebdesigner.com/">1st web designer</a>
        <a href="http://stackoverflow.com/">stackoverflow</a>
    </div>
</div>