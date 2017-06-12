<?php

include dirname(__FILE__).'/../bootstrap/doctrine.php';

$t = new lime_test();
$x = CommentTable::getInstance();

// ==========================================================================
$t->comment('CommentTable methods');
$t->can_ok($x, 'getComments', 'CommentTable has getComments method');

// ==========================================================================
$t->comment('getComments(1)');

// example result
//$r = array(
//    '5' => array(
//        'author' => 'Jan Przykladowy',
//        'date' => '2008-08-03 14:00:00',
//        'content' => 'i like this article',
//        'web' => 'http://mysite.com',
//        'mail' => 'mymail@mail.com'
//    ),
//    '6' => array(
//        'author' => 'Roman Przykladowy',
//        'date' => '2008-08-03 14:00:00',
//        'content' => 'i think this is interesting',
//        'web' => null
//        'mail' => null
//    )
//);

$r = $x->getComments(1);
$t->isa_ok($r, 'array', 'getComment returns array');
$t->cmp_ok(count($r), '===', 4, 'returned array has 4 items');

foreach($r as $k => $v) {
    $t->isa_ok($v, 'array', 'level 1 item is an array');
    $t->cmp_ok(count($v), '===', 5, 'level 1 array contains 5 items');

    $vk = array_keys($v);
    $t->cmp_ok($vk[0], '===', 'author', 'first key is author');
    $t->isa_ok($v[$vk[0]], 'string', 'first item is string');

    $t->cmp_ok($vk[1], '===', 'date', 'second key is date');
    $t->isa_ok($v[$vk[1]], 'string', 'second item is string');

    $t->cmp_ok($vk[2], '===', 'content', 'third key is content');
    $t->isa_ok($v[$vk[2]], 'string', 'third item is string');
    
    $t->cmp_ok($vk[3], '===', 'web', 'fourth key is web');
    // $t->isa_ok($v[$vk[3]], 'string', 'fourth item is string'); // string or null
    
    $t->cmp_ok($vk[4], '===', 'mail', 'fifth key is mail');
    //$t->isa_ok($v[$vk[4]], 'string', 'fifth item is string'); // string or null
}

// no comments for this article
$r = $x->getComments(9);
$t->cmp_ok($r, '===', null, 'returns null if article has no comments');

?>
