<?php
/**
 * Description of footerComponent
 *
 * @author piotrekk8
 */
class footerComponent extends sfComponent {
    
    public function execute($request) {
        // categories
        $ct = CategoryTable::getInstance();
        $this->categories = $ct->getCategoryNames();

        // articles
        $at = ArticleTable::getInstance();
        // TODO
        $cnt = 3;
        
        if($this->categories && count($this->categories) > 3) {
            $cnt = floor(count($this->categories) * 3 / 4);
        }
        $this->articles = $at->getLatestPublishedArticleTitles($cnt);
    }
}
?>
