<?php


require_once(dirname(__FILE__).'/../private_html/config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
