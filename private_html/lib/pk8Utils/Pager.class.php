<?php
/**
 * Pager class implements pagination of items which are large in count.
 * Pager returns page count, checks if given page nr is valid, returns
 * range of elements by a page nr and checks if a page is first or last page.
 *
 * @author piotrekk8
 */
class Pager {

    private $_articleCnt;
    private $_articlesOnPage;
    private $_pageCnt;

    /**
     * Pager constructor. Parameters $articleCount and $articlesOnPage should be
     * passed otherwise an exception is thrown
     *
     * @param integer $articleCount count of all articles
     * @param integer $articlesOnPage the maximal count of articles on a single
     * page
     */
    public function  __construct($articleCount, $articlesOnPage) {
        if($articleCount == null || $articlesOnPage == null) {
            throw new Exception('wrong parameters passed');
        }
        $this->_articleCnt = $articleCount;
        $this->_articlesOnPage = $articlesOnPage;
        $this->_pageCnt = (integer)ceil($this->_articleCnt / $this->_articlesOnPage);
    }

    /**
     * Returns page count
     * 
     * @return integer returns page count
     */
    public function pageCount() {
        return $this->_pageCnt;
    }

    /**
     * Checks if $pageNr is valid
     *
     * @param integer $pageNr page nr that should be checked
     * @return boolean retruns true if page nr is valid, false otherwise
     */
    public function isValidPage($pageNr) {
        return $pageNr >= 1 && $pageNr <= $this->_pageCnt;
    }

    /**
     * Returns range of items on a page specified by page nr. Range is
     * represented by array with two elements which you can access by keys:
     * "limit" - limit (count) of elements on page
     * "start" - index of element that is first element on page
     *
     * Use this method with @link Pager#isValidPage to check if the passed
     * page nr is valid
     * 
     * @param integer $pageNr the number of page that the range shlould be
     * returned for
     * @return array returns array with keys "limit" and "start"
     */
    public function itemRange($pageNr) {
        return array(
            'limit' => $this->_articlesOnPage,
            'start' => (($pageNr - 1) * $this->_articlesOnPage)
        );
    }

    /**
     * Checks if passed page nr is nr of the last page
     *
     * @param integer $pageNr number of page that should be checked
     * @return boolean returns true if $pageNr is number of last page,
     * otherwise false
     */
    public function isLastPage($pageNr) {
        return $pageNr == $this->_pageCnt;
    }

    /**
     * Checks if passed page nr is nr of the first page
     *
     * @param integer $pageNr number of page that should be checked
     * @return boolean returns true if $pageNr is number of first page,
     * otherwise false
     */
    public function isFirstPage($pageNr) {
        return $pageNr == 1;
    }
}
?>
