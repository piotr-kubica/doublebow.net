<?php
include dirname(__FILE__).'/../bootstrap/doctrine.php';

$t = new lime_test();

// ==========================================================================
printf("\n");
$t->comment("endsWith");
$a = 'testing';
$x = 'ing';
$y = 'jng';

$t->isa_ok(ArrayOptimizer::endsWith($a, $x), 'string', 'returns string type is string contains suffix');
$t->cmp_ok(ArrayOptimizer::endsWith($a, $x), '===', 'test', 'returns begining of string that contains the suffix');
$t->cmp_ok(ArrayOptimizer::endsWith($a, $y), '===', null, 'returns null for string that doesn\'t contain suffix');

// ==========================================================================
printf("\n");
$t->comment("MakeElementValueToKey");

$a = array();
array_push($a, array(
    'id_key' => 'k1',
    'data' => 'example data 1'
));
array_push($a, array(
    'id_key' => 'k2',
    'data' => 'example data 2'
));
array_push($a, array(
    'id_keI' => 'k3',
    'data' => 'example data 3'
));

//var_dump($a);

$ar = array(
    'k1' => array(
        'data' => 'example data 1'
    ),
    'k2' => array(
        'data' => 'example data 2'
    ),
    '2' => array(
        'id_keI' => 'k3',
        'data' => 'example data 3'
    )
);

//var_dump($ar);
$res = ArrayOptimizer::MakeElementValueToKey($a, '_key');
//var_dump($res);
$t->isa_ok($res, 'array', 'refernece to array is returned');
$t->cmp_ok($res, '===', $ar, 'values of first matching key suffixes are turned into whole array key');

// ==========================================================================
printf("\n");
$t->comment("ExplodeItemsToAssocArray");

$b = array(
    "key" => array(
        "data_split" => "key1*data value 1;;key2*data value 2"
    )
);

$br = array(
    "key" => array(
        "data" => array(
            "key1" => "data value 1",
            "key2" => "data value 2"
        )
    )
);

$res = ArrayOptimizer::ExplodeItemsToAssocArray($b, '_split', ';;', '*');
$t->isa_ok($res, 'array', 'refernece to array is returned');
$t->cmp_ok($res, '===', $br, 'split by given delimiters array reference is returned');

try {
    $pNull = null;
    $res = ArrayOptimizer::ExplodeItemsToAssocArray($pNull);
    $t->fail('exception not thrown');
} catch(Exception $e) {
    $t->pass('exception thrown when parameter $a not specified');
}

try {
    $res = ArrayOptimizer::ExplodeItemsToAssocArray($c, '', ';;', '*');
    $t->fail('exception not thrown');
} catch(Exception $e) {
    $t->pass('exception thrown when parameter $splitKeySuffix not specified');
}

try {
    $res = ArrayOptimizer::ExplodeItemsToAssocArray($c, '_split', '', '*');
    $t->fail('exception not thrown');
} catch(Exception $e) {
    $t->pass('exception thrown when parameter $itemSeparator not specified');
}

try {
    $res = ArrayOptimizer::ExplodeItemsToAssocArray($c, '_split', ';;', '');
    $t->fail('exception not thrown');
} catch(Exception $e) {
    $t->pass('exception thrown when parameter $keyValueSeparator not specified');
}

?>
