<?php
include dirname(__FILE__).'/../bootstrap/doctrine.php';
include dirname(__FILE__).'/ArticleTableTestFunctions.php';

$t = new lime_test();
$x = ArticleTable::getInstance();

// ==========================================================================
$t->comment('ArticleTable methods');

$t->can_ok($x, 'getPublishedArticleYearMonth', 'Article table has method getPublishedArticleYearMonth');
$t->can_ok($x, 'getPublishedArticleCount', 'Article table has method getPublishedArticleCount');
$t->can_ok($x, 'getMostCommentedPublishedArticles', 'Article table has method getMostCommentedPublishedArticles');
$t->can_ok($x, 'getLatestPublishedArticles', 'Article table has method getLatestPublishedArticles');
$t->can_ok($x, 'getPublishedArticles', 'Article table has method getPublishedArticles');
$t->can_ok($x, 'getPublishedArticleCategories', 'Article table has method getPublishedArticleCategory');
$t->can_ok($x, 'getPublishedCategoryArticles', 'Article table has method getPublishedCategoryArticles');
//$t->can_ok($x, 'getSearchResultCount', 'Article table has method getSearchResultCount');
$t->can_ok($x, 'searchArticles', 'Article table has method searchArticles');
$t->can_ok($x, 'getArticle', 'Article table has method getArticle');
$t->can_ok($x, 'getLatestPublishedArticleTitles', 'Article table has method getLatestPublishedArticleTitles');

// ==========================================================================
$t->comment('getPublishedArticleCount');
$r = $x->getPublishedArticleCount();
$t->isa_ok($r, 'integer', 'returned an integer');
$t->cmp_ok($r, '>=', 0, 'article count is a positive value');
$t->cmp_ok($r, '===', 12, 'there are 12 published articles in database');

$t->comment('getPublishedArticleCount(\'October 2010\')');
$r = $x->getPublishedArticleCount('2010 October');
$t->isa_ok($r, 'integer', 'returned an integer');
$t->cmp_ok($r, '>=', 0, 'article count is a positive value');
$t->cmp_ok($r, '===', 2, 'there are 2 published articles in October, 2010');

// ==========================================================================
$t->comment('getLatestPublishedArticles(5, 0)');

// example result
//$r = array(
//    'article-slug-1' => array(
//        'title' => 'article title 1',
//        'date' => new DateTime('2008-08-03 14:00:00'),
//        'abstract' => 'this is a test abstract',
//        'categories' => array(
//            'category-slug-1' => 'category name 1',
//            'category-slug-2' => 'category name 2'
//        ),
//        'comment_count' => 3
//    ),
//    'article-slug-2' => array(
//        'title' => 'article title 2',
//        'date' => new DateTime('2008-08-03 13:00:00'),
//        'abstract' => 'this is a test abstract',
//        'categories' => array(
//            'category-slug-1' => 'category name 1',
//            'category-slug-2' => 'category name 2'
//        ),
//        'comment_count' => 4
//    )
//);

$r = $x->getLatestPublishedArticles(5, 0);
$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 5, '5 items were fetched');
testArticle($t, $r);
testArticleDateOrder($t, $r);

$t->comment('getLatestPublishedArticles(5, 10)');
$r = $x->getLatestPublishedArticles(5, 10);
$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 2, '2 items were fetched');

$t->comment('getLatestPublishedArticles(5, 20)');
$r = $x->getLatestPublishedArticles(5, 20);
$t->is($r, null, 'null returned if limit out of range');



// ==========================================================================
$t->comment('getMostCommentedPublishedArticles(5, 0)');
$r = $x->getMostCommentedPublishedArticles(5, 0);
$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 5, '5 items were fetched');
testArticle($t, $r);
testArticleCommmentCountOrder($t, $r);

$t->comment('getMostCommentedPublishedArticles(5, 10)');
$r = $x->getMostCommentedPublishedArticles(5, 10);
$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 2, '2 items were fetched');

$t->comment('getMostCommentedPublishedArticles(5, 20)');
$r = $x->getMostCommentedPublishedArticles(5, 20);
$t->is($r, null, 'null returned if limit out of range');


// ==========================================================================
$t->comment('getPublishedArticleYearMonth');

// example result
//$r = array(
//    '2010' => array(
//        'February' => '3'
//    ),
//    '2009' => array(
//        'January' => '3',
//        'March' => '1',
//        'April' => '2',
//        'December' => '3'
//    )
//);
$r = $x->getPublishedArticleYearMonth();

function monthsInOrderASC($month1, $month2) {
    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July',
                    'August', 'September', 'October', 'November', 'December');
    $im1 = -1;
    $im2 = -1;

    for($i = 0; $i < count($months); $i++) {
        if($months[$i] === $month1) {
            $im1 = $i;
        }
    }
    for($i = 0; $i < count($months); $i++) {
        if($months[$i] === $month2) {
            $im2 = $i;
        }
    }

    if(!($im1 >= 0 && $im2 > $im1)) {
        printf("\n\n $months[$im1]  $months[$im2] \n\n");
    }

    return ($im1 >= 0 && $im2 > $im1);
}

$t->isa_ok($r, 'array', 'getPublishedArticleYearMonth returns an array');
$t->cmp_ok(count($r), '===', 2, 'returned array contains 2 elements');

// for sorting test LASTEST -> OLDEST
$i = 0;
$lastYear = null;

foreach($r as $k => $v) {
    $t->isa_ok($v, 'array', 'value is an array');

    // check year order
    if($i > 0) {
        $t->cmp_ok(intval($lastYear), '>', intval($k), 'array is ordered by years DESC');
    }

    $j = 0;
    $lastMoth = null;

    // check month order
    foreach($v as $kv => $vv) {
        if($j > 0) {
            $t->cmp_ok(monthsInOrderASC($lastMoth, $kv), '===', true, 'year array is ordered by months ASC');
            $t->isa_ok($vv, 'string', 'year array value constains a string');
            $t->cmp_ok(intval($vv), '>=', 0, 'year array (int of) value equals at least 0');
        }
        $lastMoth = $kv;
        $j++;
    }
    $lastYear = $k;
    $i++;
}

// ==========================================================================
$t->comment('getPublishedArticles(\'2011 January\', 5, 0)');
$r = $x->getPublishedArticles('2011 January', 5, 0);
$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 5, '5 items were fetched');
testArticle($t, $r);
testArticleDateOrder($t, $r);

$t->comment('getPublishedArticles(\'2011 January\', 5, 10)');
$r = $x->getPublishedArticles('2011 January', 5, 5);
$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 2, '2 items were fetched');

$t->comment('getPublishedArticles(\'2011 January\', 5, 20)');
$r = $x->getPublishedArticles('2011 January', 5, 20);
$t->is($r, null, 'null returned if limit out of range');

// ==========================================================================
$t->comment('getPublishedArticleCategories(\'category-1\')');
$r = $x->getPublishedArticleCategories('category-1');

$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 2, '2 items were fetched');
$t->isa_ok($r['article_cnt'], 'string', 'article_cnt is a string');
$t->isa_ok($r['category_name'], 'string', 'category_name is a string');
$t->cmp_ok(intval($r['article_cnt']), '>=', 0, 'intval of article_cnt at least equals 0');
$t->cmp_ok(intval($r['article_cnt']), '===', 3, 'intval of article_cnt equals 3');

$r = $x->getPublishedArticleCategories('category-5');
$t->is($r, null, 'null returned if category doesn\'t exist');

// ==========================================================================
$t->comment('getPublishedCategoryArticles(\'category-1\', 5, 0)');
$r = $x->getPublishedCategoryArticles('category-1', 5, 0);
$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 3, '3 items were fetched');
testArticle($t, $r);
testArticleDateOrder($t, $r);

$t->comment('getPublishedCategoryArticles(\'category-1\', 5, 1)');
$r = $x->getPublishedCategoryArticles('category-1', 5, 1);
$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 2, '2 items were fetched');

$t->comment('getPublishedCategoryArticles(\'category-1\', 5, 20)');
$r = $x->getPublishedCategoryArticles('category-1', 5, 20);
$t->is($r, null, 'null returned if limit out of range');

// ==========================================================================
$t->comment('getArticle(\'article-title-1\')');

// example result
//$r = array(
//    'article_id' => 3,
//    'article_slug' => 'article-slug1',
//    'title' => 'example title',
//    'date' => '2008-08-03 14:00:00',
//    'content' => 'this is an example content',
//    'categories' => array(
//        'category-slug1' => 'category_name1',
//        'category-slug2' => 'category_name2'
//    ),
//    'comment_count' => '3',
//    'author' => 'First Lastname'
//);

$r = $x->getArticle('article-title-1');
$t->isa_ok($r, 'array', 'getArticle returns an array');
$t->cmp_ok(count($r), '===', 9, 'array contains 9 items');

$rk = array_keys($r);

$t->isa_ok($r[$rk[0]], 'string', 'first element is a string');
$t->cmp_ok(intval($r[$rk[0]]), '===', 1, 'first element equals 1');
$t->cmp_ok($rk[0], '===', 'article_id', 'first element key is article_id');

$t->isa_ok($r[$rk[1]], 'string', 'second element is a string');
$t->cmp_ok($rk[1], '===', 'article_slug', 'second element key is article_slug');

$t->isa_ok($r[$rk[2]], 'string', 'third element is a string');
$t->cmp_ok($rk[2], '===', 'title', 'third element key is title');

$t->isa_ok($r[$rk[3]], 'string', 'fourth element is a string');
$t->cmp_ok($rk[3], '===', 'date', 'fourth element key is date');

$t->isa_ok($r[$rk[4]], 'string', 'fifth element is a string');
$t->cmp_ok($rk[4], '===', 'abstract', 'fifth element key is abstract');

$t->isa_ok($r[$rk[5]], 'string', 'sixth element is a string');
$t->cmp_ok($rk[5], '===', 'content', 'sixth element key is content');

$t->isa_ok($r[$rk[6]], 'string', 'seventh element is a string');
$t->cmp_ok($rk[6], '===', 'comment_count', 'seventh element key is comment_count');

$t->isa_ok($r[$rk[7]], 'string', 'eight element is a string');
$t->cmp_ok($rk[7], '===', 'author', 'eight element key is comment_count');

$t->isa_ok($r[$rk[8]], 'array', 'ninth element is an array');
$t->cmp_ok($rk[8], '===', 'categories', 'ninth element key is categories array');

$t->cmp_ok(count($r[$rk[8]]), '===', 2, 'array contains 2 items');
$rkk = array_keys($r[$rk[8]]);
$t->cmp_ok($rkk[0], '===', 'category-1', 'first category array item key equals category-1');
$t->cmp_ok($rkk[1], '===', 'category-3', 'second category array item key equals category-3');
$t->cmp_ok($r[$rk[8]][$rkk[0]], '===', 'category 1', 'first category array item value equals \'category 1\'');
$t->cmp_ok($r[$rk[8]][$rkk[1]], '===', 'category 3', 'second category array item value equals \'category 3\'');

// article slug that doesn't exist
$r = $x->getArticle('article-title-29');
$t->cmp_ok($r, '===', null, 'null if article-slug doesn\'t exist');

// ==========================================================================
//$t->comment('getSearchResultCount');
//$keywords1 = array('programming', 'java', 'oop');
//$r = $x->getSearchResultCount($keywords1);
//
//$t->isa_ok($r, 'integer', 'retuned an integer');
//$t->cmp_ok($r, '>=', 0, 'items found is a positive value');
//$t->cmp_ok($r, '===', 2, '2 items found');

// ==========================================================================
$t->comment('searchArticles(\'keywords1\'), 5, 0');
$keywords1 = array('programming', 'java', 'oop');
$r = $x->searchArticles($keywords1, 5, 0);
$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 2, '2 items were fetched'); // articles: 1,2
testArticle($t, $r);
testArticleSearch($t, $r, $keywords1);

$t->comment('searchArticles(keywords2, 5, 0)');
$keywords2 = array('ala', 'kot', 'przyklad');
$r = $x->searchArticles($keywords2, 5, 0);
$t->isa_ok($r, 'array', 'retuned an array');
$t->cmp_ok(count($r), '===', 3, '3 items were fetched');
testArticleSearch($t, $r, $keywords2);

$t->comment('searchArticles(keywords1, 5, 20)');
$r = $x->searchArticles($keywords1, 5, 20);
$t->is($r, null, 'null returned if limit out of range');

// ==========================================================================
$t->comment('getLatestPublishedArticleTitles(5, 0)');
$r = $x->getLatestPublishedArticleTitles(5, 0);
$t->isa_ok($r, 'array', 'returns an array');
$t->cmp_ok(count($r), '===', 5, '5 items were fetched');

foreach($r as $k => $v) {
    $t->isa_ok($v, 'array', 'item value is an array');
    $t->cmp_ok(count($v), '===', 2, 'item value has 2 elements');
    
    $ak = array_keys($v);
    $t->cmp_ok($ak[0], '===', 'title', 'first key is category_slug');
    $t->cmp_ok($ak[1], '===', 'category_slug', 'second key is title');
    

    $t->isa_ok($v[$ak[0]], 'string', 'first value is a string');
    $t->isa_ok($v[$ak[1]], 'string', 'second value is a string');
}

$t->comment('getLatestPublishedArticleTitles(5, 10)');
$r = $x->getLatestPublishedArticleTitles(5, 10);
$t->isa_ok($r, 'array', 'returns an array');
$t->cmp_ok(count($r), '===', 2, '2 items were fetched');
