<?php echo '<?xml version=\'1.0\' encoding=\'utf-8\'?>'; ?>
<rss version="2.0">
    <channel>
        <title>DoubleBow Latest Articles</title>
        <link>http://doublebow.net</link>
        <description>Web developers blog</description>
        <language>en</language>
        <copyright>Copyright 2011 Piotr Kubica</copyright>
        <?php if($articles): ?>
            <?php foreach($articles as $slug => $article): ?>
            <?php
                include_partial('rssitem',
                    array('article' => $article,
                          'slug' => $slug,
                          'categories' => $article['categories']));
            ?>
            <?php endforeach; ?>
        <?php endif;?>
    </channel>
</rss>