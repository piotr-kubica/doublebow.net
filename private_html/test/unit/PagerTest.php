<?php

include dirname(__FILE__).'/../bootstrap/doctrine.php';

$t = new lime_test();
$t->comment('Pager constructor');

try {
    $p = new Pager(null, 5);
    $t->fail('article count must be specified');
} catch(Exception $e) {
    $t->pass('if article count is not specified exception is thrown');
}

try {
    $p = new Pager(21, null);
    $t->fail('article count on page must be specified');
} catch (Exception $e) {
    $t->pass('fi article count on page is not specified an exception is thrown');
}

$p = new Pager(21, 5);

$t->comment('Pager methods');
$t->can_ok($p, 'pageCount', 'Page class object has pageCount method');
$t->can_ok($p, 'isValidPage', 'Page class object has isValidPage method');
$t->can_ok($p, 'itemRange', 'Page class object has itemRange method');
$t->can_ok($p, 'isLastPage', 'Page class object has isLastPage method');
$t->can_ok($p, 'isFirstPage', 'Page class object has isFirstPage method');

$t->comment('pageCount');
$t->isa_ok($p->pageCount(), 'integer', 'pageCount method returns an integer');
$t->is($p->pageCount(), 5, 'pageCount returns 5');

$t->comment('isValidPage');
$t->isa_ok($p->isValidPage(3), 'boolean', 'isValidPage returns a boolean');
$t->is($p->isValidPage(3), true, 'page nr 3 is valid');
$t->is($p->isValidPage(5), true, 'page nr 5 is valid');
$t->is($p->isValidPage(-1), false, 'page nr -1 is not valid');
$t->is($p->isValidPage(0), false, 'page nr 0 is not valid');
$t->is($p->isValidPage(6), false, 'page nr 6 is not valid');

$t->comment('isLastPage');
$t->isa_ok($p->isLastPage(2), 'boolean', 'isLastPage returns a boolean');
$t->is($p->isLastPage(2), false, 'page 2 is not last');
$t->is($p->isLastPage(0), false, 'page 0 is not last');
$t->is($p->isLastPage(5), true, 'page 5 is last');

$t->comment('isFirstPage');
$t->isa_ok($p->isFirstPage(2), 'boolean', 'isFirstPage returns a boolean');
$t->is($p->isFirstPage(2), false, 'page 2 is not first');
$t->is($p->isFirstPage(5), false, 'page 5 is not first');
$t->is($p->isFirstPage(1), true, 'page 1 is first');

$t->comment('itemRange');
$r1 = $p->itemRange(1);
$r2 = $p->itemRange(5);
$t->isa_ok($r1, 'array', 'itemRange returns an assocciative array');
$t->cmp_ok(count($r1), '===', 2, 'itemRange returns 2 element array');
$t->isa_ok($r1['limit'], 'integer', 'itemRange returns assocciative array with key: limit');
$t->isa_ok($r1['start'], 'integer', 'itemRange returns assocciative array with key: start');

$t->cmp_ok($r1['limit'], '===', 5, 'itemRange(1) returns limit 5');
$t->cmp_ok($r1['start'], '===', 0, 'itemRange(1) returns start 0');

$t->cmp_ok($r2['limit'], '===', 5, 'itemRange(5) returns limit 5');
$t->cmp_ok($r2['start'], '===', 20, 'itemRange(5) returns start 20');

?>
