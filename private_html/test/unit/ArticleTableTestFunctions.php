<?php

// redundant code for tests HERE
function testArticle(&$t, &$r) {
    foreach($r as $k => $v) {
        // check level 0 key
        $t->isa_ok($k, 'string', 'array key at level 0 is a string');

        // check level 0 value
        $t->isa_ok($v, 'array', 'value at level 0 is an array');
        $t->cmp_ok(count($v), '===', 5, 'each array at level 1 array contains exactly 5 elements');

        // check level 1 array
        $kv = array_keys($v);

        // check title
        $t->cmp_ok($kv[0], '===', 'title', 'first article data field is: title');
        $t->isa_ok($v[$kv[0]], 'string', 'first article data field is a string');

        // check date
        $t->cmp_ok($kv[1], '===', 'date', 'second article data field is: date');
        $t->isa_ok($v[$kv[1]], 'string', 'second article data field is a string');

        // check abstract
        $t->cmp_ok($kv[2], '===', 'abstract', 'third article data field is: abstract');
        $t->isa_ok($v[$kv[2]], 'string', 'third article data field is a string');

        // check comment count
        $t->cmp_ok($kv[3], '===', 'comment_count', 'fourth article data field is: comment_count');
        $t->isa_ok($v[$kv[3]], 'string', 'fourth article data field is an string');

        // check categories array
        $t->cmp_ok($kv[4], '===', 'categories', 'fifth article data field is: categories');
        $t->isa_ok($v[$kv[4]], 'array', 'fifth article data field is an array');

        $rt = $v[$kv[4]];
        $t->is(empty($rt), false, 'categories array is not empty');

        // check category array items
        foreach($rt as $catk => $catv) {
            $t->isa_ok($catk, 'string', 'categories array key is a string');
            $t->isa_ok($catv, 'string', 'categories array item is a string');
        }
    }
}

// test date order (sorted DESC)
function testArticleDateOrder(&$t, &$r) {
    $lastDate = null;
    $i = 0;

    foreach($r as $k => $v) {
        $kv = array_keys($v);

        // check if sorted
        if($i > 0) {
            $t->cmp_ok((new DateTime($lastDate)), '>', (new DateTime($v[$kv[1]])), 'previous item has an earlier date');
        }
        $lastDate = $v[$kv[1]];
        $i++;
    }
}

// test comment count order (sorted DESC)
function testArticleCommmentCountOrder(&$t, &$r) {
    $lastCommentCount = 0;
    $i = 0;

    foreach($r as $k => $v) {
        $kv = array_keys($v);
        
        // check if sorted
        if($i > 0) {
            $t->cmp_ok($lastCommentCount, '>=', intval($v[$kv[4]]), 'previous item has more or equal comment count');
        }
        $lastCommentCount = intval($v[$kv[4]]);
        $i++;
    }
}

function testArticleSearch(&$t, &$r, &$keywords) {
    $keywordAltString = implode('|', $keywords);
    $patt = "#" . $keywordAltString . "#";
    $lastHitCnt = null;
    $ma;

    foreach($r as $k => $v) {
        $kv = array_keys($v);
        
        // concat title and abstract
        $titAbs = $v[$kv[0]] . ' ' . $v[$kv[2]];

        if($lastHitCnt != null) {
            $thisHitCnt = preg_match_all($patt, $titAbs, $ma);
            $t->cmp_ok($lastHitCnt, '>=', $thisHitCnt, 'previous item has more or equal keyword match count');
            $lastHitCnt = $thisHitCnt;
        } else {
            $lastHitCnt = preg_match_all($patt, $titAbs, $ma);
        }
        
    }
}

?>
