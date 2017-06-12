<?php

// XXX prod
require_once 'C:\\symfony-1.4.6\\lib/autoload/sfCoreAutoload.class.php';
//require_once '/home/d2bow/domains/d2bow.vot.pl/symfony-1.4.6/lib/autoload/sfCoreAutoload.class.php';
//require_once '/home/d2bow/domains/doublebow.net/symfony-1.4.6/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
	$this->setWebDir($this->getRootDir().'/../../public_html');
  }
}
