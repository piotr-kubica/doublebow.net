<?php

/**
 * errors actions.
 *
 * @package    doublebow2
 * @subpackage errors
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class errorsActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeError404(sfWebRequest $request){
        $cols = sfConfig::get('app_col_cnt_404');
        $minItemsCnt = sfConfig::get('app_min_items_in_col_404');
        $ct = CategoryTable::getInstance();
        $this->categories = $ct->getCategories();
        $acnt = $minItemsCnt * $cols; // max $minItemsCnt article titles  per column when categories not found or count < $minItemsCnt

        if($this->categories && count($this->categories) >= $minItemsCnt) {
            // articles per column (there are $cols columns) for latest articles in 404 page
            $acnt = count($this->categories) * $cols;
        }
        $at = ArticleTable::getInstance();
        $this->articles = $at->getLatestPublishedArticles($acnt);
        $this->cols = $cols;

        if($this->articles) {
            $this->ak = array_keys($this->articles);
            $this->colCnt = floor(min($acnt, count($this->articles)) / $cols);
        }
    }

    public function executeError500(sfWebRequest $request){
        // don't connect with database
    }
}
