<?php

/**
 * rss actions.
 *
 * @package    doublebow2
 * @subpackage rss
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class rssActions extends sfActions
{
    public function executeIndex(sfWebRequest $request) {
        $at = ArticleTable::getInstance();
        $item_cnt = sfConfig::get('app_rss_max_items');
        $this->articles = $at->getLatestPublishedArticles($item_cnt, 0);
    }
}
