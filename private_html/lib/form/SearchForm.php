<?php
/**
 * Description of SearchForm
 *
 * @author piotrekk8
 */
class SearchForm extends BaseForm {
    
    public function configure() {
        $this->setWidgets(array('pkeywords'  => new sfWidgetFormInputText(array(), array('size' => '26', 'maxlength' => '26'))));
    }
}
?>
