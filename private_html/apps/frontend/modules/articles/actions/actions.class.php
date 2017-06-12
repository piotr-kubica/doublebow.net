<?php

/**
 * articles actions.
 *
 * @package    doublebow2
 * @subpackage articles
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class articlesActions extends sfActions
{
    public function executeArticle(sfWebRequest $request) {
        $aslug = $request->getParameter('aslug');

        if(!$aslug) {
            $this->forward404();
        }
        $at = ArticleTable::getInstance();
        $this->article = $at->getArticle($aslug);

        if(!$this->article) {
            $this->forward404();
        }
        $aid = intval($this->article['article_id']);
        $ct = CommentTable::getInstance();
        $this->comments = $ct->getComments($aid);
        
        // create form
        $this->form = new CommentForm();

        if($request->isMethod('post')) {
            // addition elements remain the same if posted
            $sum = $this->getUser()->getAttribute('sum');
            $this->add1 = $sum[0];
            $this->add2 = $sum[1];
            $this->form->bind($request->getParameter('a_comment'));
            
            if ($this->form->isValid()){
                $params = $this->form->getValues();
                $this->addComment($aid, $params["author"], $params["content"], $params["web"], $params["mail"]);
                $this->redirect($request->getUri());
            }
        } else {
            $sum = $this->getHumanTestAddition();
            $this->getUser()->setAttribute('sum', $sum);
            $this->add1 = $sum[0];
            $this->add2 = $sum[1];
        }
    }

    private function addComment($article_id, $author, $content, $web, $mail) {
        $comment = new Comment();
        $comment->setArray(array(
            'author'     => $author,
            'date'       => date('Y-m-d H:i:s'),
            'content'    => $content,
            'web'        => $web,
            'mail'       => $mail,
            'article_id' => $article_id
        ));
        $comment->save();
    }

    private function getHumanTestAddition() {
        $sn = array('one', 'two', 'three', 'four');
        $k1 = mt_rand(1, count($sn));
        $k2 = mt_rand(1, count($sn));
        return array(strval($k1), $sn[$k2 - 1], strval($k1 + $k2));
    }

    public function executeLatest(sfWebRequest $request) {
        $at = ArticleTable::getInstance();
        $article_cnt = $at->getPublishedArticleCount();

        if($article_cnt < 1) {
            $this->forward404();
        }
        $max_articles = sfConfig::get('app_max_articles_on_page');
        $this->pager = new Pager($article_cnt, $max_articles);
        $page_nr = $request->getParameter('nr');

        if($page_nr == '') {
            $page_nr = 1;
        } else if(!$this->pager->isValidPage($page_nr)) {
            $this->forward404();
        }
        $range = $this->pager->itemRange($page_nr);
        $this->articles = $at->getLatestPublishedArticles($range['limit'], $range['start']);

        if(!$this->articles) {
            $this->forward404();
        }
        $this->current_page = $page_nr;
    }

    public function executeMostcommented(sfWebRequest $request) {
        $at = ArticleTable::getInstance();
        $article_cnt = $at->getPublishedArticleCount();

        if($article_cnt < 1) {
            $this->forward404();
        }
        $max_articles = sfConfig::get('app_max_articles_on_page');
        $this->pager = new Pager($article_cnt, $max_articles);
        $page_nr = $request->getParameter('nr');

        if($page_nr == '') {
            $page_nr = 1;
        } else if(!$this->pager->isValidPage($page_nr)) {
            $this->forward404();
        }
        $range = $this->pager->itemRange($page_nr);
        $this->articles = $at->getMostCommentedPublishedArticles($range['limit'], $range['start']);

        if(!$this->articles) {
            $this->forward404();
        }
        $this->current_page = $page_nr;
    }

    public function executeArchive(sfWebRequest $request) {
        $at = ArticleTable::getInstance();
        $year = $request->getParameter('year');
        $month = $request->getParameter('month');

        if(!$year || !$month) {
            $this->forward404();
        }
        $ym = $year . ' ' . $month;
        $article_cnt = $at->getPublishedArticleCount($ym);

        if($article_cnt < 1) {
            $this->forward404();
        }
        $max_articles = sfConfig::get('app_max_articles_on_page');
        $this->pager = new Pager($article_cnt, $max_articles);
        $page_nr = $request->getParameter('nr');

        if($page_nr == '') {
            $page_nr = 1;
        } else if(!$this->pager->isValidPage($page_nr)) {
            $this->forward404();
        }
        $range = $this->pager->itemRange($page_nr);
        $this->articles = $at->getPublishedArticles($ym, $range['limit'], $range['start']);

        if(!$this->articles) {
            $this->forward404();
        }
        $this->year = $year;
        $this->month = $month;
        $this->current_page = $page_nr;
    }

    public function executeCategory(sfWebRequest $request) {
        $at = ArticleTable::getInstance();
        $cslug = $request->getParameter('cslug');
        
        if(!$cslug) {
            $this->forward404();
        }
        $cat = $at->getPublishedArticleCategories($cslug);

        if(intval($cat['article_cnt']) < 1) {
            $this->forward404();
        }
        $max_articles = sfConfig::get('app_max_articles_on_page');
        $this->pager = new Pager(intval($cat['article_cnt']), $max_articles);
        $page_nr = $request->getParameter('nr');

        if($page_nr == '') {
            $page_nr = 1;
        } else if(!$this->pager->isValidPage($page_nr)) {
            $this->forward404();
        }
        $range = $this->pager->itemRange($page_nr);
        $this->articles = $at->getPublishedCategoryArticles($cslug, $range['limit'], $range['start']);

        if(!$this->articles) {
            $this->forward404();
        }
        $this->cslug = $cslug;
        $this->cat_name = $cat['category_name'];
        $this->current_page = $page_nr;
    }

    public function executeSearch(sfWebRequest $request) {
        if($request->isMethod('post')) {
            $keywords = explode(' ', $request->getParameter('pkeywords'));

            foreach($keywords as $k => $v) {
                $keywords[$k] = trim($v);
            }
            // FIXME change form action in production env
            $this->redirect('/articles/search/'.implode(',', $keywords));
        }
        // else
        $keywords = $request->getParameter('keywords');
        $this->search_results = null;

        if(!$keywords) {
            return sfView::SUCCESS;
        }
        $this->keywords = explode(',', $keywords);
        
        if(!$this->keywords) {
            return sfView::SUCCESS;
        }
        $at = ArticleTable::getInstance();
        $article_cnt = $at->searchArticleCnt($this->keywords);

        if($article_cnt < 1) {
            return sfView::SUCCESS;
        }
        $max_articles = sfConfig::get('app_max_articles_on_page');
        $this->pager = new Pager($article_cnt, $max_articles);
        $page_nr = $request->getParameter('nr');

        if($page_nr == '') {
            $page_nr = 1;
        } else if(!$this->pager->isValidPage($page_nr)) {
            $this->forward404();
        }
        $range = $this->pager->itemRange($page_nr);
        $this->search_results = $at->searchArticles($this->keywords, $range['limit'], $range['start']);

        if(!$this->search_results) {
            $this->forward404();
        }
        $this->keywords = implode(' ', $this->keywords);
        $this->result_index = $range['start'] + 1;
        $this->current_page = $page_nr;
    }
}
