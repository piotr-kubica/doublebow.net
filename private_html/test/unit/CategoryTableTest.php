<?php

include dirname(__FILE__).'/../bootstrap/doctrine.php';

$t = new lime_test();
$x = CategoryTable::getInstance();

// ==========================================================================
$t->comment('CategoryTable methods');
$t->can_ok($x, 'getCategoryNames', 'CategoryTable has getCategoryName method');
$t->can_ok($x, 'getCategories', 'CategoryTable has getCategories method');

// ==========================================================================
$t->comment('getCategories()');

// example result
//$r = array(
//    'category-slug1' => array(
//        'name' => 'category_name1',
//        'description' => 'category_description example 1',
//        'article_count' => '3'
//    ),
//    'category-slug2' => array(
//        'name' => 'category_name2',
//        'description' => 'category_description example 2',
//        'article_count' => '1'
//    )
//);

$r = $x->getCategories();

$t->isa_ok($r, 'array', 'getCategories returns an array');
$t->cmp_ok(count($r), '===', 4, 'array has 4 items');

foreach($r as $k => $v) {
    $vk = array_keys($v);

    $t->cmp_ok($vk[0], '===', 'name', 'first item key is name');
    $t->isa_ok($v[$vk[0]], 'string', 'first item is a string');

    $t->cmp_ok($vk[1], '===', 'description', 'second item key is description');
    $t->isa_ok($v[$vk[1]], 'string', 'second item is a string');
    
    $t->cmp_ok($vk[2], '===', 'article_cnt', 'third item key is article_cnt');
    $t->cmp_ok(intval($v[$vk[2]]), '>=', 1, 'article_cnt is at least 1');
    $t->isa_ok($v[$vk[2]], 'string', 'third item is a string');
}

// ==========================================================================
$t->comment('getCategoryNames');

// example result
//$r = array(
//    'category-slug1' => 'category-name1',
//    'category-slug2' => 'category-name2',
//    'category-slug3' => 'category-name3'
//);

$r = $x->getCategoryNames();
$t->isa_ok($r, 'array', 'getCategoryNames returns an array');
$t->cmp_ok(count($r), '===', 4, 'array has 4 items');

foreach($r as $k => $v) {
    $t->isa_ok($v, 'string', 'array item is a string');
}

?>
