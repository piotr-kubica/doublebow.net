<?php

/**
 * categories actions.
 *
 * @package    doublebow2
 * @subpackage categories
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class categoriesActions extends sfActions
{
    public function executeIndex(sfWebRequest $request) {
        $ct = CategoryTable::getInstance();
        $this->categories = $ct->getCategories();

        if(!$this->categories) {
            $this->forward404();
        }
    }
}
