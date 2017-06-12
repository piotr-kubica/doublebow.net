<item>
    <?php
        $categories = $sf_data->getRaw('categories');
        $carrk = array_keys($categories);
        $firstCategotySlug = $carrk[0];
    ?>
    <guid>
        <?php echo url_for('http://doublebow.net/articles/' . $firstCategotySlug . '/' . $slug); ?>
    </guid>
    <title>
        <?php echo $article['title'] ?>
    </title>
    <link>
        <?php echo url_for('http://doublebow.net/articles/' . $firstCategotySlug . '/' . $slug); ?>
    </link>
    <description>
        <?php echo $article['abstract'] ?>
    </description>
    <?php foreach($categories as $catSlug => $catName): ?>
        <category domain="<?php echo url_for('/articles/' . $catSlug); ?>">
            <?php echo $catName ?>
        </category>
    <?php endforeach; ?>
    <pubDate>
        <?php echo date_format(date_create($article['date']), 'r'); ?>
    </pubDate>
</item>