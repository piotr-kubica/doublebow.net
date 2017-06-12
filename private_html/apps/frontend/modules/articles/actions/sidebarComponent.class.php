<?php
/**
 * Description of sidebarComponent
 *
 * @author piotrekk8
 */
class sidebarComponent extends sfComponent {
    
    public function execute($request) {
        // publication years
        $at = ArticleTable::getInstance();
        $this->publYears = $at->getPublishedArticleYearMonth();

        // categories
        $ct = CategoryTable::getInstance();
        $this->categories = $ct->getCategoryNames();
    }
}
?>
