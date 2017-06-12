<?php
include dirname(__FILE__).'/../bootstrap/doctrine.php';

//$t = new lime_test();
//
//$sa = array();
//array_push($sa, 'programming');
//array_push($sa, 'java');
//array_push($sa, 'oop');
//
//var_dump(ArticleTable::getInstance()->getLatestPublishedArticles(5, 10));

$a = array (
    "key1"=> array(
        "categories" => "k1*data value 1;;k2*data value 2"
    ),
    "key2"=> array(
        "categories" => "k1*data value 1;;k2*data value 2"
    )
);
ArticleTable::getInstance()->getLatestPublishedArticles(5, 10);

//$t = '';
//echo (!$t) ? "T" : "N";

//$t = new lime_test(6);
//$t->comment('basic tests');

//printf("" . ceil(6 / 4) . "");

/*
$b = array();
$t = ($b == null);
printf($t? "Y" : "N");

$a = array(
    'a' => 'vala',
    'b' => 'valb'
);

var_dump($a);

unset($a['a']);

var_dump($a);
//*/
/*
$keywords = array('programming', 'java', 'oop');
$keywordPattern = implode("|", $keywords);
$patt = "#" . $keywordPattern . "#";
//echo $patt;
$text1 = 'programming in java is cool but not so cool as programming in c++';
$text2 = 'oop programming is a vastly used paradigm nowadays';
$text3 = 'your grandmother shit her pants. that\'s all';
$ma;

$a = preg_match_all($patt, $text1, $ma);
echo "found: " . $a . "\n";
$a = preg_match_all($patt, $text2, $ma);
echo "found: " . $a . "\n";
$a = preg_match_all($patt, $text3, $ma);
echo "found: " . $a . "\n";
//*/
/*
$d = new DateTime();
echo gettype($d) . ' ' . get_class($d) . ' ';
$t->isa_ok($d, 'DateTime', 'variable is a date type');

$a = array();
$b = array('k1' => 'a', 'k2' => 'b', 'k3' => 'c');

$t->isa_ok($a, 'array', 'variable a is an array');
$t->is(empty($a), true, 'a is an empty array');

$t->isa_ok($b, 'array', 'variable b is an array');
$t->cmp_ok(count($b), '===', 3, 'array b size is 3');
$kb = array_keys($b);
$t->cmp_ok($kb[0], '===', 'k1', 'first key is k1');

$tmp = new Pager();
echo $tmp->__toString();

$tmpRes = ArticleTable::getInstance()->getTest();

var_dump($tmpRes);

// COMPARE ARRAYS
$tmpa = array();
array_push($tmpa, 'val1');
array_push($tmpa, 'val2');

foreach($tmpa as $tk => $tv) {
    echo 'key: ' . $tk . ' value: ' . $tv;
}

$tmpa2 = array(
    '0' => 'val1',
    '1' => 'val2'
);

printf("\n");
echo $tmpa === $tmpa2 ? "YES" : "NO";
printf("\n");

// EXPLODE TEST
$ea = explode(';', null);
var_dump($ea);
*/
?>
