<?php
/**
 * ArrayOptimizer methods MakeElementValueToKey and ExplodeItemsToAssocArray
 * are compresing arrays by removing redundant data structures to make more
 * efficient and lighter.
 *
 * @author piotrekk8
 */
class ArrayOptimizer {

    /**
     * Method takes first array $a element value which key suffix is specified by parameter
     * $keySuffix and makes it a new key for the whole array.
     * Method Can be used for big data arrays fetched from database.
     *
     * For example
     * MakeElementValueToKey($arr, '_key');
     * gets the value of nested array (nested level 1) with key that
     * contains suffix '_key' and makes the value the key for the array
     *
     * array(2) {
     *     [0]=> array(2) {
     *         ["id_key"]=> "1"
     *         ["data"]=> "example data 1"
     *     }
     *     [1]=> array(2) {
     *         ["id_key"]=> "2"
     *         ["data"]=> "example data 2"
     *     }
     * }
     *
     * The array above will be turned into
     *
     * array(2) {
     *     ["1"]=> array(1) {
     *         ["data"]=> "example data 1"
     *     }
     *     ["2"]=> array(2) {
     *         ["data"]=> "example data 2"
     *     }
     * }
     * 
     * @param array $a array which key should be changed
     * @param string $keySuffix key suffix for value that should be new key
     * @return array modified array with new key
     */
    public static function &MakeElementValueToKey(&$a, $keySuffix = '_key') {
        if(!$a || !$keySuffix) {
            throw new Exception('not valid parameter value');
        }
        $ca = array();
        $nk_set = false; // new key set to false

        foreach($a as $k => $v) {
            // compress key
            if(is_array($v)) {
                foreach($v as $kv => $vv) {
                    if(ArrayOptimizer::endsWith($kv, $keySuffix) != null
                       && !isset($ca[$vv])) {

                        unset($a[$k][$kv]);
                        $ca[$vv] = &$a[$k];
                        $nk_set = true;
                        break;
                    }
                }
            }
            if(!$nk_set) {
                $ca[$k] = &$v;
            }
            $nk_set = false;
        }
        return $ca;
    }

    /**
     * Method splits vlues of an array $a which key suffix contains suffix
     * $splitKeySuffix into array items and key=>value pairs by given
     * separators $itemSeparator and $keyValueSeparator.
     *
     * Example:
     * ExplodeItemsToAssocArray($a, '_split', ';;', '*');
     *
     * array(1) {
     *     ["key"]=> array(1) {
     *         ["data_split"]=> "key1*data value 1;;key2*data value 2"
     *     }
     * }
     *
     * will return the following array
     *
     * array(1) {
     *     ["key"]=> array(1) {
     *         ["data"]=> array(2) {
     *             ["key1"] => "data value 1"
     *             ["key2"] => "data value 2"
     *         }
     *     }
     * }
     *
     * If one of $splitAssocArrayKeySuffix, $itemSplitSeparator,
     * $keyValueSplitSeparator not specified no changes are made.
     *
     * @param array $a array which item value should be split
     * @param string $splitKeySuffix suffix of key which value should be split
     * @param string $itemSeparator separator by which value should be split
     * into items
     * @param string $keyValueSeparator separator by which items should be
     * split into key=>value pairs
     * @return array returns array with value split into items and key=>value pairs
     */
    /*
    public static function &ExplodeItemsToAssocArray(&$a, $splitKeySuffix = '_split',
            $itemSeparator = ';;', $keyValueSeparator = '*') {
        if(!$a || !$splitKeySuffix || !$itemSeparator || !$keyValueSeparator) {
            throw new Exception('not valid parameter value');
        }
        foreach($a as $k => $v) {
            foreach($v as $kv => $vv) {
                if(($pref = ArrayOptimizer::endsWith($kv, $splitKeySuffix)) && ($items = explode($itemSeparator, $vv))) {
                    $na = array();

                    for($i = 0, $cnt = count($items); $i < $cnt; $i++) {
                        if(($ekv = explode($keyValueSeparator, $items[$i])) && count($ekv) == 2) {
                            $na[$ekv[0]] = $ekv[1];
                        }
                    }
                    if($na) {
                        $a[$k][$pref] = &$na;
                        unset($a[$k][$kv]);
                    }
                }
            }
        }
        return $a;
    }//*/

    // XXX fixed ExplodeItemsToAssocArray version
    public static function &ExplodeItemsToAssocArray_2(
            &$a, $splitKey, $itemSeparator = ';;', $keyValueSeparator = '*')
    {
        if(!$a || !$splitKey || !$itemSeparator || !$keyValueSeparator) {
            throw new Exception('not valid parameter value');
        }
        foreach($a as $k => $v) {
            if(array_key_exists($splitKey, $v) && ($items = explode($itemSeparator, $v[$splitKey]))) {
                $a[$k][$splitKey] = array();

                for($i = 0, $cnt = count($items); $i < $cnt; $i++) {
                    if(($ekv = explode($keyValueSeparator, $items[$i])) && count($ekv) == 2) {
                        $a[$k][$splitKey][$ekv[0]] = $ekv[1];
                    } else {
                        array_push($a[$k][$splitKey], $items[$i]);
                    }
                }
            }
        }
        return $a;
    }

    /* TODO modify visibility to private after testing
     * 
     * Checks if string $haystack ends with another string and returns prefix if
     * phrase ends with suffix. Returns null if doesn't end.
     * Ex. "moreover" ends with "over" so "more" would be returned.
     *
     * @param string $haystack the phrase in wich we search for the suffix
     * @param string $needle the phrase we are looking for
     * @return string returns prefix if phrase $haystack has suffix $needle
     * ortherwise returns null
     */
    public static function endsWith($haystack, $needle) {
        $len = strlen($haystack) - strlen($needle);

        if(strcmp(substr($haystack, $len), $needle) === 0){
            return substr($haystack, 0, $len);
        } else {
            return null;
        }
    }
}
?>
