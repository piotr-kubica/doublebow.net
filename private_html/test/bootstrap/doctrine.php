<?php
include(dirname(__FILE__).'/unit.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
new sfDatabaseManager($configuration);
Doctrine_Core::loadData(sfConfig::get('sf_dev_dir').'/fixtures');
?>
